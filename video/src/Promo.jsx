import React from 'react';
import {
  AbsoluteFill,
  Img,
  OffthreadVideo,
  Sequence,
  interpolate,
  spring,
  staticFile,
  useCurrentFrame,
  useVideoConfig,
} from 'remotion';
import data from '../data/site.json';

/*
 * Video promosi 9:16, 28 detik.
 *   0-5 dtk   : Pembuka, maskot Reno menyapa
 *   5-14 dtk  : Pilihan armada (klip layar ponsel dari HyperFrames, detik 0-9)
 *   14-23 dtk : Klik Sewa -> chat WhatsApp (klip yang sama, detik 9-18)
 *   23-28 dtk : Penutup + ajakan
 */
export const FPS = 30;
const S = (seconds) => Math.round(seconds * FPS);

const T_INTRO_END = S(5);
const T_BOOKING = S(14);
const T_OUTRO = S(23);
export const TOTAL_FRAMES = S(28);
const OVERLAP = 8;

const NAVY = '#142536';
const AMBER = '#ffbe3d';
const BROWN = '#936000';
const MUTED = '#53606c';
const FONT = '"PJS", system-ui, sans-serif';

const fontCss = [500, 600, 700, 800]
  .map((w) => `@font-face{font-family:"PJS";font-weight:${w};src:url("${staticFile(`fonts/pjs-${w}.woff2`)}") format("woff2");}`)
  .join('');

const clamp = { extrapolateLeft: 'clamp', extrapolateRight: 'clamp' };

/** Muncul dengan pegas mulai frame `at`: 0 -> 1. */
const usePop = (at, config = { damping: 13, stiffness: 140 }) => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();
  return spring({ frame: frame - at, fps, config });
};

/** Latar hangat dengan lingkaran cahaya amber dan garis rute. */
const Background = () => {
  const frame = useCurrentFrame();
  const drift = Math.sin(frame / 45) * 30;
  return (
    <AbsoluteFill style={{ background: 'linear-gradient(160deg,#fcfaf6 0%,#fff3dc 100%)' }}>
      <div style={{ position: 'absolute', left: -200 + drift, top: 980, width: 1500, height: 1500, borderRadius: '50%', background: 'radial-gradient(circle,rgba(249,217,146,.55),rgba(249,217,146,0) 68%)' }} />
      <div style={{ position: 'absolute', left: 420 - drift, top: -420, width: 1100, height: 1100, borderRadius: '50%', background: 'radial-gradient(circle,rgba(255,190,61,.28),rgba(255,190,61,0) 70%)' }} />
      <Img src={staticFile('brand/route.svg')} style={{ position: 'absolute', left: -140, top: 560, width: 1360, opacity: 0.55 }} />
    </AbsoluteFill>
  );
};

/** Logo perusahaan (atau ikon bawaan) + nama. */
const Brand = ({ size = 96, fontSize = 64 }) => (
  <div style={{ display: 'flex', alignItems: 'center', gap: size * 0.28, fontFamily: FONT, fontWeight: 800, fontSize, letterSpacing: '-0.03em', color: NAVY }}>
    {data.site.hasLogo ? (
      <Img src={staticFile(data.site.logo)} style={{ height: size, width: 'auto' }} />
    ) : (
      <div style={{ width: size, height: size, borderRadius: size * 0.28, background: AMBER, display: 'grid', placeItems: 'center' }}>
        <svg width={size * 0.58} height={size * 0.58} viewBox="0 0 24 24" fill="none" stroke={NAVY} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
          <path d="M5 13l1.5-4.5A2 2 0 0 1 8.4 7h7.2a2 2 0 0 1 1.9 1.5L19 13" />
          <path d="M3 13h18v4a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H6v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-4z" />
          <path d="M7 15.5h.01M17 15.5h.01" />
        </svg>
      </div>
    )}
    <span>{data.site.name}</span>
  </div>
);

/** Maskot Reno: masuk memantul, lalu mengayun pelan. */
const Reno = ({ pose, at = 0, width, left, bottom, from = 'bottom', flip = false }) => {
  const frame = useCurrentFrame();
  const pop = usePop(at, { damping: 11, stiffness: 120 });
  const t = Math.max(0, frame - at);
  const bob = Math.sin(t / 14) * 10;
  const tilt = Math.sin(t / 22) * 2.2;
  const enterX = from === 'left' ? (1 - pop) * -700 : from === 'right' ? (1 - pop) * 700 : 0;
  const enterY = from === 'bottom' ? (1 - pop) * 900 : 0;
  return (
    <div style={{ position: 'absolute', left, bottom, width, transform: `translate(${enterX}px, ${enterY + bob}px) rotate(${tilt}deg) scaleX(${flip ? -1 : 1})`, transformOrigin: '50% 90%', filter: 'drop-shadow(0 30px 26px rgba(20,37,54,.20))' }}>
      <Img src={staticFile(`brand/reno-${pose}.png`)} style={{ width: '100%', display: 'block' }} />
    </div>
  );
};

/** Teks yang naik dan muncul. */
const Rise = ({ at, children, style }) => {
  const pop = usePop(at, { damping: 18, stiffness: 120 });
  return <div style={{ opacity: pop, transform: `translateY(${(1 - pop) * 60}px)`, ...style }}>{children}</div>;
};

/** Keluar halus di akhir sebuah adegan. */
const useExit = (duration, length = 12) => {
  const frame = useCurrentFrame();
  return interpolate(frame, [duration - length, duration], [1, 0], clamp);
};

// ------------------------------------------------------------------ Adegan 1
const Intro = () => {
  const exit = useExit(T_INTRO_END);
  const bubble = usePop(S(1.0), { damping: 10, stiffness: 160 });
  return (
    <AbsoluteFill style={{ opacity: exit, fontFamily: FONT, color: NAVY }}>
      <Rise at={S(1.9)} style={{ position: 'absolute', top: 210, width: '100%', display: 'flex', justifyContent: 'center' }}>
        <Brand size={120} fontSize={92} />
      </Rise>
      <Rise at={S(2.4)} style={{ position: 'absolute', top: 380, width: '100%', textAlign: 'center', fontSize: 54, fontWeight: 600, color: MUTED, letterSpacing: '-0.01em' }}>
        Teman di setiap perjalanan
      </Rise>

      <Reno pose="wave" at={S(0.2)} width={1160} left={-40} bottom={40} />

      <div style={{ position: 'absolute', left: 470, top: 620, transform: `scale(${bubble}) rotate(${(1 - bubble) * -12}deg)`, transformOrigin: '10% 100%' }}>
        <div style={{ background: '#fff', border: `5px solid ${AMBER}`, borderRadius: 48, padding: '30px 46px', fontSize: 62, fontWeight: 800, letterSpacing: '-0.02em', boxShadow: '0 20px 50px rgba(20,37,54,.14)', whiteSpace: 'nowrap' }}>
          Hai, aku Reno!
        </div>
        <div style={{ position: 'absolute', left: 70, bottom: -24, width: 46, height: 46, background: '#fff', borderRight: `5px solid ${AMBER}`, borderBottom: `5px solid ${AMBER}`, transform: 'rotate(45deg)' }} />
      </div>
    </AbsoluteFill>
  );
};

// ------------------------------------------------------------ Adegan 2 dan 3
const PHONE_W = 560;
const PHONE_H = Math.round((PHONE_W * 1920) / 1080);
const BEZEL = 20;

const Phone = () => {
  const pop = usePop(S(0.2), { damping: 15, stiffness: 90 });
  const frame = useCurrentFrame();
  const float = Math.sin(frame / 30) * 6;
  return (
    <div style={{ position: 'absolute', left: 1080 - PHONE_W - BEZEL * 2 - 40, top: 610, transform: `translateY(${(1 - pop) * 1500 + float}px) rotate(${(1 - pop) * 8}deg)` }}>
      <div style={{ padding: BEZEL, borderRadius: 78, background: NAVY, boxShadow: '0 50px 90px rgba(20,37,54,.30), 0 0 0 4px rgba(255,255,255,.5) inset' }}>
        <div style={{ width: PHONE_W, height: PHONE_H, borderRadius: 60, overflow: 'hidden', background: '#fff', position: 'relative' }}>
          <OffthreadVideo src={staticFile('screen.mp4')} muted style={{ width: PHONE_W, height: PHONE_H, display: 'block' }} />
        </div>
      </div>
    </div>
  );
};

/** Judul besar di atas ponsel; berganti saat masuk adegan "sewa". */
const Headline = ({ at, eyebrow, lines, note }) => (
  <div style={{ position: 'absolute', left: 64, top: 110, width: 952, fontFamily: FONT, color: NAVY }}>
    <Rise at={at} style={{ fontSize: 34, fontWeight: 800, letterSpacing: '0.16em', color: BROWN }}>{eyebrow}</Rise>
    {lines.map((line, i) => (
      <Rise key={i} at={at + 5 + i * 5} style={{ marginTop: i === 0 ? 18 : 0, fontSize: 96, fontWeight: 800, letterSpacing: '-0.05em', lineHeight: 1.08, color: line.accent ? BROWN : NAVY, whiteSpace: 'nowrap' }}>
        {line.text}
      </Rise>
    ))}
    {note ? <Rise at={at + 20} style={{ marginTop: 26, fontSize: 44, fontWeight: 600, color: MUTED, letterSpacing: '-0.01em' }}>{note}</Rise> : null}
  </div>
);

/** Label kecil di sisi kiri ponsel. */
const SideTag = ({ at, top, children, tone = 'light' }) => {
  const pop = usePop(at, { damping: 12, stiffness: 150 });
  const dark = tone === 'dark';
  return (
    <div style={{ position: 'absolute', left: 48, top, transform: `translateX(${(1 - pop) * -420}px)`, opacity: pop, fontFamily: FONT }}>
      <div style={{ display: 'inline-flex', alignItems: 'center', gap: 14, height: 80, padding: '0 28px', borderRadius: 40, background: dark ? NAVY : '#fff', color: dark ? '#fff' : NAVY, border: dark ? 'none' : '3px solid #eadfc8', fontSize: 33, fontWeight: 700, boxShadow: '0 14px 34px rgba(20,37,54,.12)', whiteSpace: 'nowrap' }}>
        {children}
      </div>
    </div>
  );
};

const Dot = ({ color }) => <span style={{ width: 18, height: 18, borderRadius: '50%', background: color, display: 'inline-block' }} />;

// Sama dengan urutan filter di hf-screen/index.html: kategori mobil unggulan paling akhir.
const FILTER_ORDER = data.categories
  .filter((c) => c !== data.featured.category)
  .slice(0, 3)
  .concat([data.featured.category]);

const FleetCopy = () => {
  const exit = useExit(T_BOOKING - T_INTRO_END);
  return (
    <AbsoluteFill style={{ opacity: exit }}>
      <Headline
        at={S(0.3)}
        eyebrow="PILIH ARMADAMU"
        lines={[{ text: `${data.carCount} pilihan mobil` }, { text: 'siap jalan', accent: true }]}
        note={`Mulai ${data.minPriceText}/hari`}
      />
      {/* urutan dan waktunya mengikuti pergantian filter di klip layar (2,0 dtk lalu tiap 1,7 dtk) */}
      {FILTER_ORDER.map((category, i) => (
        <SideTag key={category} at={S(2.0) + i * S(1.7)} top={800 + i * 104}>
          <Dot color={AMBER} /> {category}
        </SideTag>
      ))}
    </AbsoluteFill>
  );
};

const BookingCopy = () => {
  const exit = useExit(T_OUTRO - T_BOOKING);
  const steps = [
    { at: S(0.6), label: '1. Pilih mobil' },
    { at: S(1.6), label: '2. Klik Sewa' },
    { at: S(3.0), label: '3. Chat WhatsApp', dark: true },
  ];
  return (
    <AbsoluteFill style={{ opacity: exit }}>
      <Headline
        at={S(0.2)}
        eyebrow="SEWA ITU MUDAH"
        lines={[{ text: 'Klik Sewa,' }, { text: 'langsung chat', accent: true }]}
        note="Terhubung ke WhatsApp kami"
      />
      {steps.map((step, i) => (
        <SideTag key={step.label} at={step.at} top={800 + i * 104} tone={step.dark ? 'dark' : 'light'}>
          {step.label}
        </SideTag>
      ))}
    </AbsoluteFill>
  );
};

const Demo = () => {
  const duration = T_OUTRO - T_INTRO_END;
  const exit = useExit(duration, 14);
  const frame = useCurrentFrame();
  const slideOut = interpolate(frame, [duration - 14, duration], [0, -120], clamp);
  return (
    <AbsoluteFill style={{ opacity: exit, transform: `translateY(${slideOut}px)` }}>
      <Phone />
      <Sequence durationInFrames={T_BOOKING - T_INTRO_END}><FleetCopy /></Sequence>
      <Sequence from={T_BOOKING - T_INTRO_END}><BookingCopy /></Sequence>
      <Reno pose="point" at={S(0.5)} width={440} left={-36} bottom={40} from="left" />
    </AbsoluteFill>
  );
};

// ------------------------------------------------------------------ Adegan 4
const Outro = () => {
  const frame = useCurrentFrame();
  // Tidak memudar di akhir: bingkai terakhir tetap menampilkan ajakan dan nomor.
  const fade = 1;
  const button = usePop(S(1.5), { damping: 10, stiffness: 150 });
  const pulse = 1 + Math.sin(Math.max(0, frame - S(2)) / 6) * 0.018;
  return (
    <AbsoluteFill style={{ fontFamily: FONT, color: NAVY, opacity: fade }}>
      <Rise at={S(0.2)} style={{ position: 'absolute', top: 150, width: '100%', display: 'flex', justifyContent: 'center' }}>
        <Brand size={96} fontSize={72} />
      </Rise>
      <Rise at={S(0.5)} style={{ position: 'absolute', top: 320, width: '100%', textAlign: 'center', fontSize: 112, fontWeight: 800, letterSpacing: '-0.05em', lineHeight: 1.08 }}>
        Sewa mobil
      </Rise>
      <Rise at={S(0.7)} style={{ position: 'absolute', top: 444, width: '100%', textAlign: 'center', fontSize: 112, fontWeight: 800, letterSpacing: '-0.05em', lineHeight: 1.08, color: BROWN }}>
        sekarang juga
      </Rise>

      <Reno pose="key" at={S(0.3)} width={900} left={90} bottom={400} />

      <div style={{ position: 'absolute', left: 90, bottom: 250, width: 900, transform: `scale(${button * pulse})`, opacity: Math.min(1, button) }}>
        <div style={{ height: 150, borderRadius: 44, background: NAVY, color: '#fff', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 26, fontSize: 60, fontWeight: 800, letterSpacing: '-0.02em', boxShadow: '0 30px 60px rgba(20,37,54,.28)' }}>
          <span style={{ width: 30, height: 30, borderRadius: '50%', background: '#25d366', display: 'inline-block' }} />
          Chat WhatsApp
        </div>
      </div>
      <Rise at={S(1.9)} style={{ position: 'absolute', bottom: 130, width: '100%', textAlign: 'center', fontSize: 64, fontWeight: 800, letterSpacing: '-0.01em' }}>
        {data.site.phone}
      </Rise>
    </AbsoluteFill>
  );
};

// ---------------------------------------------------------------------- Utama
export const Promo = () => (
  <AbsoluteFill style={{ background: '#fcfaf6' }}>
    <style>{fontCss}</style>
    <Background />
    <Sequence durationInFrames={T_INTRO_END}><Intro /></Sequence>
    {/* Adegan saling tumpang 8 frame supaya tidak ada jeda kosong di antaranya. */}
    <Sequence from={T_INTRO_END - OVERLAP} durationInFrames={T_OUTRO - T_INTRO_END}><Demo /></Sequence>
    <Sequence from={T_OUTRO - OVERLAP - 6}><Outro /></Sequence>
  </AbsoluteFill>
);
