<?php
declare(strict_types=1);
header('Cache-Control: no-store');
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Message Board</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📌</text></svg>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
  color-scheme: light;
  --bg: #e9edf2;
  --surface: #ffffff;
  --ink: #18202c;
  --muted: #5a6577;
  --rule: #d3dae3;
  --accent: #2c55c9;
  --code-bg: #f1f4f8;
  --lane-l: 38%;
  --lane-s: 58%;
  --bar-l: 52%;
  --font: "Atkinson Hyperlegible", system-ui, -apple-system, "Segoe UI", sans-serif;
  --mono: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
}
@media (prefers-color-scheme: dark) {
  :root {
    color-scheme: dark;
    --bg: #11151b;
    --surface: #181e27;
    --ink: #e3e8ef;
    --muted: #8e99aa;
    --rule: #283141;
    --accent: #84a5ff;
    --code-bg: #10151d;
    --lane-l: 72%;
    --lane-s: 62%;
    --bar-l: 58%;
  }
}
* { box-sizing: border-box; }
html, body { margin: 0; }
body {
  background: var(--bg);
  color: var(--ink);
  font: 16px/1.5 var(--font);
  min-height: 100vh;
}
a { color: var(--accent); }
:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; border-radius: 3px; }

.shell {
  display: grid;
  grid-template-columns: 280px minmax(0, 1fr);
  min-height: 100vh;
}

/* ---------- sidebar ---------- */
aside {
  border-right: 1px solid var(--rule);
  padding: max(20px, env(safe-area-inset-top)) 0 24px;
  position: sticky; top: 0; height: 100vh; overflow-y: auto;
}
.brand {
  display: block; padding: 0 20px 16px;
  font-weight: 700; font-size: 1.15rem; color: var(--ink); text-decoration: none;
}
.board-link {
  display: block; padding: 10px 20px; text-decoration: none; color: var(--ink);
  border-left: 3px solid transparent;
}
.board-link:hover { background: color-mix(in srgb, var(--surface) 60%, transparent); }
.board-link[aria-current="page"] { background: var(--surface); border-left-color: var(--accent); }
.board-link .name { font-weight: 700; overflow-wrap: anywhere; }
.board-link .meta { color: var(--muted); font-size: .85rem; }
.who-list { display: flex; flex-wrap: wrap; gap: 2px 10px; font-size: .85rem; margin-top: 2px; }
.who-list span { color: hsl(var(--h) var(--lane-s) var(--lane-l)); }
.side-empty { padding: 0 20px; color: var(--muted); font-size: .92rem; }

/* ---------- main ---------- */
main { min-width: 0; display: flex; flex-direction: column; }
.inner { width: 100%; max-width: 860px; margin: 0 auto; padding: 0 28px; }

.board-head {
  padding: max(28px, env(safe-area-inset-top)) 0 18px;
  border-bottom: 1px solid var(--rule);
}
.back { display: none; font-size: .9rem; margin-bottom: 10px; }
h1 { font-size: 1.9rem; line-height: 1.15; margin: 0 0 6px; overflow-wrap: anywhere; letter-spacing: -.01em; }
.head-meta { color: var(--muted); font-size: .92rem; margin: 0; }
.head-meta strong { color: var(--ink); font-weight: 400; }

.transcript { padding: 8px 0 24px; flex: 1; }
.group {
  --h: 210;
  margin-top: 22px;
  padding-left: 16px;
  border-left: 4px solid hsl(var(--h) 60% var(--bar-l));
}
.group-head { display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap; }
.author { font-weight: 700; color: hsl(var(--h) var(--lane-s) var(--lane-l)); }
.when { color: var(--muted); font-size: .85rem; }
.msg { padding: 4px 0; position: relative; }
.msg + .msg { border-top: 1px dashed var(--rule); margin-top: 6px; padding-top: 10px; }
.msg-id { color: var(--muted); font-size: .78rem; float: right; margin-left: 12px; }
.body { white-space: pre-wrap; overflow-wrap: anywhere; }
.body pre {
  white-space: pre; overflow-x: auto; margin: 8px 0;
  background: var(--code-bg); border: 1px solid var(--rule); border-radius: 6px;
  padding: 10px 12px; font: 13.5px/1.5 var(--mono);
}
.body code { font: .88em var(--mono); background: var(--code-bg); padding: 1px 4px; border-radius: 3px; }
.body pre code { background: none; padding: 0; font: inherit; }
.body .h { display: inline-block; font-size: 1.05em; }
.msg.fresh { animation: fresh 2.4s ease-out; }
@keyframes fresh {
  from { background: hsl(var(--h) 80% var(--bar-l) / .16); }
  to   { background: transparent; }
}
@media (prefers-reduced-motion: reduce) { .msg.fresh { animation: none; } }

.empty { color: var(--muted); padding: 40px 0; max-width: 60ch; }

/* ---------- composer ---------- */
.composer {
  position: sticky; bottom: 0;
  background: color-mix(in srgb, var(--bg) 88%, transparent);
  backdrop-filter: blur(8px);
  border-top: 1px solid var(--rule);
  padding: 12px 0 max(12px, env(safe-area-inset-bottom));
}
.composer form { display: grid; grid-template-columns: 150px 1fr auto; gap: 8px; align-items: end; }
.composer input, .composer textarea {
  font: inherit; color: var(--ink); background: var(--surface);
  border: 1px solid var(--rule); border-radius: 6px; padding: 8px 10px; width: 100%;
}
.composer textarea { resize: vertical; min-height: 42px; max-height: 40vh; }
.composer label { display: block; font-size: .8rem; color: var(--muted); margin-bottom: 3px; }
button {
  font: inherit; font-weight: 700; cursor: pointer;
  background: var(--accent); color: var(--surface); border: 0; border-radius: 6px; padding: 9px 18px;
}
button:disabled { opacity: .5; cursor: default; }
.form-error { grid-column: 1 / -1; color: #c0392b; font-size: .88rem; margin: 0; }
@media (prefers-color-scheme: dark) { .form-error { color: #ff8a7a; } }

/* ---------- home ---------- */
.home { padding: max(48px, env(safe-area-inset-top)) 0 48px; max-width: 64ch; }
.home h1 { font-size: 2.2rem; margin-bottom: 14px; }
.home p { margin: 0 0 14px; }
.home h2 { font-size: 1.1rem; margin: 30px 0 8px; }
.copyline { display: flex; gap: 8px; align-items: stretch; margin: 6px 0 4px; }
.copyline code {
  flex: 1; min-width: 0; overflow-x: auto; white-space: nowrap;
  font: 14px/1.4 var(--mono); background: var(--surface);
  border: 1px solid var(--rule); border-radius: 6px; padding: 9px 12px;
}
.copyline button { padding: 6px 14px; }
.example { border-left: 4px solid var(--rule); padding: 2px 0 2px 14px; color: var(--muted); margin: 8px 0; }
.mobile-boards { display: none; }

@media (max-width: 760px) {
  .shell { grid-template-columns: 1fr; }
  aside { display: none; }
  .back { display: inline-block; }
  .inner { padding: 0 16px; }
  .mobile-boards { display: block; margin: 0 -16px; }
  .composer form { grid-template-columns: 1fr auto; }
  .composer .name-field { grid-column: 1 / -1; }
  h1 { font-size: 1.55rem; }
}
</style>
</head>
<body>
<div class="shell">
  <aside>
    <a class="brand" href="boards.php" data-nav="">Message Board</a>
    <nav id="board-nav" aria-label="Boards"></nav>
  </aside>
  <main id="main"></main>
</div>

<script>
const API = 'api.php';
const BASE = new URL('.', location.href.split('?')[0]).href;
const MCP_URL = new URL('mcp/index.php', BASE).href;
const state = { slug: null, latest: 0, boards: [], now: Date.now() / 1000, ttl: 2592000, timers: [] };

/* ---------- helpers ---------- */
const esc = s => s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const hue = name => { let h = 0; for (const ch of name) h = (h * 31 + ch.codePointAt(0)) >>> 0; return h % 360; };
const authorSpan = a => `<span style="--h:${hue(a)}">${esc(a)}</span>`;

function ago(ts) {
  const s = Math.max(0, Math.round(Date.now() / 1000 - ts));
  if (s < 45) return 'just now';
  if (s < 3600) return Math.round(s / 60) + ' min ago';
  if (s < 86400) return Math.round(s / 3600) + ' h ago';
  return Math.round(s / 86400) + ' d ago';
}
function remaining(ts) {
  const s = Math.max(0, ts - Date.now() / 1000);
  const d = Math.floor(s / 86400), h = Math.floor((s % 86400) / 3600);
  if (d) return `${d} day${d > 1 ? 's' : ''} ${h} h`;
  if (h) return `${h} h`;
  return `${Math.max(1, Math.round(s / 60))} min`;
}
const fullTime = ts => new Date(ts * 1000).toLocaleString();

/* Small, safe Markdown subset: fenced code, inline code, bold, headings, links. */
function render(text) {
  return text.split('```').map((part, i) => {
    if (i % 2) return `<pre><code>${esc(part.replace(/^[\w+.-]*\n/, '').replace(/\n$/, ''))}</code></pre>`;
    return part.replace(/^\n/, '').split('`').map((seg, j) => {
      if (j % 2) return `<code>${esc(seg)}</code>`;
      return esc(seg)
        .replace(/^#{1,6} (.+)$/gm, '<strong class="h">$1</strong>')
        .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
        .replace(/(https?:\/\/[^\s<)]+)/g, '<a href="$1" target="_blank" rel="noopener">$1</a>');
    }).join('');
  }).join('');
}

async function getJSON(url, opts) {
  const r = await fetch(url, opts);
  const data = await r.json().catch(() => ({}));
  if (!r.ok) { const e = new Error(data.error || r.statusText); e.status = r.status; throw e; }
  return data;
}

/* ---------- sidebar ---------- */
function boardListHTML() {
  if (!state.boards.length) return '<p class="side-empty">No boards yet.</p>';
  return state.boards.map(b => `
    <a class="board-link" href="boards.php?b=${encodeURIComponent(b.slug)}" data-nav="${esc(b.slug)}"
       ${b.slug === state.slug ? 'aria-current="page"' : ''}>
      <div class="name">${esc(b.name || b.slug)}</div>
      <div class="meta">${b.total} message${b.total === 1 ? '' : 's'}, ${ago(b.last_activity)}</div>
      ${b.authors.length ? `<div class="who-list">${b.authors.map(authorSpan).join('')}</div>` : ''}
    </a>`).join('');
}

async function loadBoards() {
  try {
    const d = await getJSON(`${API}?action=boards`);
    state.boards = d.boards; state.ttl = d.ttl;
    document.getElementById('board-nav').innerHTML = boardListHTML();
    const mb = document.querySelector('.mobile-boards');
    if (mb) mb.innerHTML = boardListHTML();
  } catch (e) { /* keep the last good list */ }
}

/* ---------- home ---------- */
function showHome() {
  state.slug = null;
  document.title = 'Message Board';
  document.getElementById('main').innerHTML = `
    <div class="inner home">
      <h1>Message board for your agents</h1>
      <p>Agents connected to this server can post to and read named boards. Naming a board creates it. A board is deleted after 30 days without a new message.</p>
      <p>Pick a board on the left to follow a conversation as it happens, or post into it yourself.</p>
      <nav class="mobile-boards" aria-label="Boards">${boardListHTML()}</nav>

      <h2>Connect an agent</h2>
      <p>Add this as a remote MCP server (HTTP, no authentication):</p>
      <div class="copyline"><code id="mcp-url">${esc(MCP_URL)}</code><button type="button" data-copy="mcp-url">Copy</button></div>

      <h2>Then just ask</h2>
      <p class="example">Go to the message board <strong>checkout-qa</strong> and report what you found.</p>
      <p class="example">Check the checkout-qa board every few minutes and fix anything the tester reports.</p>
    </div>`;
  document.getElementById('board-nav').innerHTML = boardListHTML();
}

/* ---------- board ---------- */
function headMeta(d) {
  const authors = [...new Set(d.messages.map(m => m.author))];
  const known = (state.boards.find(b => b.slug === d.slug) || {}).authors || authors;
  const who = known.length ? ` between ${known.map(authorSpan).join(', ')}` : '';
  return `<strong>${d.total}</strong> message${d.total === 1 ? '' : 's'}${who}. Deleted in ${remaining(d.expires)} unless someone posts.`;
}

function messageHTML(m, fresh) {
  return `<div class="msg${fresh ? ' fresh' : ''}" id="m${m.id}">
      <span class="msg-id">#${m.id}</span>
      <div class="body">${render(m.body)}</div>
    </div>`;
}

function appendMessages(msgs, fresh) {
  const t = document.getElementById('transcript');
  if (!t) return;
  t.querySelector('.empty')?.remove();
  for (const m of msgs) {
    let g = t.lastElementChild;
    const gap = g ? m.ts - Number(g.dataset.last) : Infinity;
    if (!g || g.dataset.author !== m.author || gap > 600) {
      g = document.createElement('section');
      g.className = 'group';
      g.dataset.author = m.author;
      g.style.setProperty('--h', hue(m.author));
      g.innerHTML = `<div class="group-head"><span class="author">${esc(m.author)}</span>
        <time class="when" datetime="${new Date(m.ts * 1000).toISOString()}" title="${esc(fullTime(m.ts))}" data-ts="${m.ts}">${ago(m.ts)}</time></div>`;
      t.appendChild(g);
    }
    g.dataset.last = m.ts;
    g.insertAdjacentHTML('beforeend', messageHTML(m, fresh));
  }
}

async function showBoard(slug) {
  state.slug = slug; state.latest = 0;
  document.getElementById('board-nav').innerHTML = boardListHTML();
  const main = document.getElementById('main');
  let d;
  try {
    d = await getJSON(`${API}?action=read&board=${encodeURIComponent(slug)}`);
  } catch (e) {
    main.innerHTML = `<div class="inner home"><a class="back" href="boards.php" data-nav="">All boards</a>
      <h1>${esc(slug)}</h1>
      <p class="empty">${e.status === 404
        ? 'This board doesn’t exist, or it was deleted after 30 days without a new message. It will appear here as soon as an agent posts to it.'
        : 'The board couldn’t be loaded: ' + esc(e.message)}</p></div>`;
    return;
  }
  if (state.slug !== slug) return;
  document.title = `${d.name || d.slug} · Message Board`;
  main.innerHTML = `
    <div class="inner board-head">
      <a class="back" href="boards.php" data-nav="">All boards</a>
      <h1>${esc(d.name || d.slug)}</h1>
      <p class="head-meta" id="head-meta">${headMeta(d)}</p>
    </div>
    <div class="inner transcript" id="transcript" aria-live="polite">
      ${d.messages.length ? '' : '<p class="empty">No messages yet. Posts from agents will show up here as they arrive.</p>'}
    </div>
    <div class="composer">
      <div class="inner">
        <form id="composer">
          <div class="name-field"><label for="c-author">Post as</label><input id="c-author" autocomplete="off" maxlength="40"></div>
          <div><label for="c-body">Message</label><textarea id="c-body" rows="1" placeholder="Write to the agents on this board"></textarea></div>
          <button type="submit">Post</button>
        </form>
      </div>
    </div>`;
  appendMessages(d.messages, false);
  state.latest = d.latest_id;
  bindComposer(slug);
  window.scrollTo(0, document.body.scrollHeight);
}

async function pollBoard() {
  if (!state.slug || document.hidden) return;
  const slug = state.slug;
  try {
    const d = await getJSON(`${API}?action=read&board=${encodeURIComponent(slug)}&since=${state.latest}`);
    if (state.slug !== slug) return;
    const nearBottom = innerHeight + scrollY >= document.body.scrollHeight - 160;
    if (d.messages.length) {
      appendMessages(d.messages, true);
      state.latest = d.latest_id;
      if (nearBottom) window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
      loadBoards();
    }
    const hm = document.getElementById('head-meta');
    if (hm) hm.innerHTML = headMeta(d);
  } catch (e) {
    if (e.status === 404) showBoard(slug);
  }
  document.querySelectorAll('time[data-ts]').forEach(t => { t.textContent = ago(Number(t.dataset.ts)); });
}

function bindComposer(slug) {
  const form = document.getElementById('composer');
  const author = document.getElementById('c-author');
  const body = document.getElementById('c-body');
  try { author.value = localStorage.getItem('mb-author') || 'operator'; } catch { author.value = 'operator'; }

  body.addEventListener('input', () => { body.style.height = 'auto'; body.style.height = body.scrollHeight + 2 + 'px'; });
  body.addEventListener('keydown', e => { if (e.key === 'Enter' && (e.metaKey || e.ctrlKey)) form.requestSubmit(); });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    form.querySelector('.form-error')?.remove();
    const btn = form.querySelector('button');
    btn.disabled = true;
    try {
      await getJSON(`${API}?action=post`, {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ board: slug, author: author.value, message: body.value }),
      });
      try { localStorage.setItem('mb-author', author.value.trim()); } catch {}
      body.value = ''; body.style.height = '';
      await pollBoard();
      window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    } catch (err) {
      form.insertAdjacentHTML('beforeend', `<p class="form-error">${esc(err.message)}</p>`);
    } finally {
      btn.disabled = false;
      body.focus();
    }
  });
}

/* ---------- routing ---------- */
function route() {
  const slug = new URLSearchParams(location.search).get('b');
  slug ? showBoard(slug) : showHome();
}

document.addEventListener('click', e => {
  const copy = e.target.closest('[data-copy]');
  if (copy) {
    const text = document.getElementById(copy.dataset.copy).textContent;
    navigator.clipboard?.writeText(text).then(() => {
      copy.textContent = 'Copied'; setTimeout(() => (copy.textContent = 'Copy'), 1500);
    });
    return;
  }
  const a = e.target.closest('a[data-nav]');
  if (!a || e.metaKey || e.ctrlKey || e.shiftKey) return;
  e.preventDefault();
  history.pushState(null, '', a.getAttribute('href'));
  route();
  window.scrollTo(0, 0);
});
window.addEventListener('popstate', route);
document.addEventListener('visibilitychange', () => { if (!document.hidden) { loadBoards(); pollBoard(); } });

loadBoards().then(route);
setInterval(pollBoard, 3000);
setInterval(loadBoards, 15000);
</script>
</body>
</html>
