@include('layouts.header')

<h2 style="text-align:center;margin:16px 0;">Trabajos Realizados</h2>

<style>
  .catalogo{max-width:1200px;margin:0 auto;padding:16px}
  .grid{display:grid;grid-template-columns:1fr;gap:14px}
  @media (min-width:480px){.grid{grid-template-columns:repeat(2,1fr)}}
  @media (min-width:768px){.grid{grid-template-columns:repeat(3,1fr)}}
  @media (min-width:1024px){.grid{grid-template-columns:repeat(4,1fr)}}

  .tile{position:relative;border-radius:14px;overflow:hidden;background:#111;box-shadow:0 6px 20px rgba(0,0,0,.12);cursor:pointer;transition:.15s}
  .tile:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(0,0,0,.18)}
  .tile>img{width:100%;height:100%;aspect-ratio:1/1;object-fit:cover;display:block}
  .tile .tag{position:absolute;left:8px;bottom:8px;background:rgba(0,0,0,.6);color:#fff;padding:6px 10px;border-radius:10px;font-size:.9rem;max-width:calc(100% - 16px);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

  .modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.75);display:none;align-items:center;justify-content:center;z-index:9999;padding:16px}
  .modal-backdrop.open{display:flex}
  .modal{width:min(1000px,100%);background:#111;color:#fff;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.4)}
  .modal-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #222}
  .modal-body{position:relative}
  .modal-img{background:#000}
  .modal-img img{width:100%;height:70vh;max-height:78vh;object-fit:contain;display:block}
  .modal-info{padding:12px 16px;background:#111}
  .modal{ display:block !important; position:relative; }
  .xbtn,.navbtn{border:none;background:transparent;color:#fff;font-size:22px;cursor:pointer;line-height:1}
  .navbtn{position:absolute;top:50%;transform:translateY(-50%);font-size:34px;padding:8px 10px;background:rgba(0,0,0,.35);border-radius:10px}
  .prev{left:8px}.next{right:8px}
  .price{font-weight:700}
</style>

<div class="catalogo">
  <div class="grid">
    @foreach($productos as $i => $p)
      @php $img = $p->imagen_perfil ? asset('storage/'.$p->imagen_perfil) : asset('placeholder.jpg'); @endphp

      <button
        type="button"             {{-- <- IMPORTANTE --}}
        class="tile"
        data-index="{{ $i }}"
        data-nombre="{{ $p->nombre }}"
        data-precio="{{ number_format($p->precio, 2) }}"
        data-desc="{{ trim(preg_replace('/\s+/', ' ', $p->descripcion ?? '')) }}"
        data-img="{{ $img }}"
        aria-label="Ver {{ $p->nombre }}"
      >
        <img src="{{ $img }}" alt="{{ $p->nombre }}" loading="lazy">
        <span class="tag">{{ $p->nombre }}</span>
      </button>
    @endforeach
  </div>
</div>

<!-- Modal / Galería -->
<div id="modalBackdrop" class="modal-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="modal">
    <div class="modal-head">
      <h3 id="mNombre" style="margin:0;"></h3>
      <button class="xbtn" id="mClose" aria-label="Cerrar">✕</button>
    </div>
    <div class="modal-body">
      <button class="navbtn prev" id="mPrev" aria-label="Anterior">‹</button>
      <div class="modal-img"><img id="mImg" alt=""></div>
      <button class="navbtn next" id="mNext" aria-label="Siguiente">›</button>
    </div>
    <div class="modal-info">
      <div class="price">$ <span id="mPrecio"></span></div>
      <p id="mDesc" style="opacity:.85"></p>
    </div>
  </div>
</div>

<script>
  const cards = Array.from(document.querySelectorAll('.tile'));
  const items = cards.map(c => ({
    nombre: c.dataset.nombre, precio: c.dataset.precio,
    desc: c.dataset.desc, img: c.dataset.img
  }));

  const backdrop = document.getElementById('modalBackdrop');
  const mNombre = document.getElementById('mNombre');
  const mPrecio = document.getElementById('mPrecio');
  const mDesc   = document.getElementById('mDesc');
  const mImg    = document.getElementById('mImg');
  const mClose  = document.getElementById('mClose');
  const mPrev   = document.getElementById('mPrev');
  const mNext   = document.getElementById('mNext');

  let idx = 0;

  function render(i){
    const it = items[i];
    if(!it) return;
    idx = i;
    mNombre.textContent = it.nombre || '';
    mPrecio.textContent = it.precio || '';
    mDesc.textContent   = it.desc || '';
    mImg.src            = it.img || '';
    mImg.alt            = it.nombre || '';
  }

  function openModal(i){
    render(i);
    backdrop.classList.add('open');
    backdrop.setAttribute('aria-hidden', 'false');
    document.documentElement.style.overflow = 'hidden';
  }
  function closeModal(){
    backdrop.classList.remove('open');
    backdrop.setAttribute('aria-hidden', 'true');
    document.documentElement.style.overflow = '';
  }
  function next(){ render( (idx + 1) % items.length ); }
  function prev(){ render( (idx - 1 + items.length) % items.length ); }

  cards.forEach((card,i)=> card.addEventListener('click', () => openModal(i)));
  mClose.addEventListener('click', closeModal);
  mNext.addEventListener('click', next);
  mPrev.addEventListener('click', prev);
  backdrop.addEventListener('click', (e) => { if(e.target === backdrop) closeModal(); });
  window.addEventListener('keydown', (e) => {
    if(!backdrop.classList.contains('open')) return;
    if(e.key === 'Escape') closeModal();
    if(e.key === 'ArrowRight') next();
    if(e.key === 'ArrowLeft') prev();
  });
</script>

{{-- Botón flotante de WhatsApp --}}
@php
    $waPhone = '5491166660040';
    $waText  = 'Hola, estuve viendo su sitio web. Quiero más información.';
    $waUrl   = 'https://api.whatsapp.com/send?phone='.$waPhone.'&text='.rawurlencode($waText);
@endphp

<a href="{{ $waUrl }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp">
    <!-- SVG del ícono -->
</a>

<style>
/* Estilos del botón flotante */
</style>
{{-- Botón flotante de WhatsApp --}}
@php
    $waPhone = '5491166660040';
    $waText  = 'Hola, estuve viendo su sitio web. Quiero más información.';
    $waUrl   = 'https://api.whatsapp.com/send?phone='.$waPhone.'&text='.rawurlencode($waText);
@endphp

<a href="{{ $waUrl }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp">
  <!-- Ícono WhatsApp (SVG inline) -->
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="28" height="28" fill="white">
    <path d="M16 .4C7.2.4.1 7.5.1 16.3c0 2.9.8 5.6 2.2 8l-1.5 5.5 5.7-1.5c2.3 1.3 5 2 7.8 2 8.8 0 15.9-7.1 15.9-15.9S24.8.4 16 .4zm0 29.1c-2.6 0-5.1-.7-7.3-2l-.5-.3-3.4.9.9-3.3-.3-.5c-1.4-2.1-2.1-4.6-2.1-7.1 0-7.3 5.9-13.2 13.2-13.2 3.5 0 6.8 1.4 9.3 3.9s3.9 5.8 3.9 9.3c0 7.3-5.9 13.3-13.3 13.3zm7.4-9.9c-.4-.2-2.3-1.1-2.6-1.2-.3-.1-.6-.2-.9.2-.3.4-1 1.2-1.3 1.4-.2.2-.4.2-.8.1s-1.5-.6-2.9-1.9c-1.1-1-1.9-2.2-2.1-2.5-.2-.4 0-.6.2-.8.2-.2.4-.5.6-.8.2-.3.3-.5.5-.8.2-.3.1-.6 0-.8-.1-.2-.9-2.1-1.3-2.9-.3-.7-.6-.6-.9-.6h-.8c-.3 0-.8.1-1.2.6s-1.6 1.6-1.6 3.9 1.6 4.5 1.8 4.8c.2.3 3.1 4.7 7.6 6.4 1.1.5 2 .8 2.7 1 .9.3 1.7.3 2.3.2.7-.1 2.3-.9 2.6-1.7.3-.8.3-1.5.2-1.7s-.4-.3-.8-.5z"/>
</svg>

</a>

<style>
/* Botón flotante fijo abajo a la derecha. Queda visible al hacer scroll. */
.whatsapp-float{
  position: fixed;
  right: 18px;
  bottom: 18px;
  width: 56px; height: 56px;
  background: #25D366;
  color: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 20px rgba(0,0,0,.2);
  z-index: 1060; /* por encima del footer y navbar */
  text-decoration: none;
  transition: transform .15s ease, box-shadow .15s ease;
}
.whatsapp-float:hover{
  transform: scale(1.05);
  box-shadow: 0 10px 24px rgba(0,0,0,.25);
}
/* Ajuste responsive */
@media (max-width: 575px){
  .whatsapp-float{ right: 14px; bottom: 14px; width: 52px; height: 52px; }
}
</style>
{{-- Fin botón flotante --}}


@include('layouts.footer')
