{{-- resources/views/productos/index_public.blade.php --}}
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
  .xbtn,.navbtn{border:none;background:transparent;color:#fff;font-size:22px;cursor:pointer;line-height:1}
  .navbtn{position:absolute;top:50%;transform:translateY(-50%);font-size:34px;padding:8px 10px;background:rgba(0,0,0,.35);border-radius:10px}
  .prev{left:8px}.next{right:8px}
  .price{font-weight:700}
  /* forzamos a que nuestro modal se muestre, aunque Bootstrap tenga .modal {display:none} */
  .modal-backdrop.open .modal { display: block !important; position: relative; }

  .pill{display:inline-block; padding:6px 10px; background:#222; border-radius:10px; margin-right:8px; font-size:.9rem}
</style>

<div class="catalogo">
  <div class="grid">
    @foreach($productos as $i => $p)
      @php
        // adicionales: última primero
        $adicionales = collect($p->imagenes_adicionales ?? [])->reverse()->values();
        // principal siempre primera
        if ($p->imagen_perfil) { $adicionales->prepend($p->imagen_perfil); }
        // urls absolutas
        $arr = $adicionales->map(fn($r) => asset('storage/'.$r))->values();

        $cover = $arr[0] ?? asset('placeholder.jpg');

        // precio: ocultar si marcaron "no mostrar"
        $precio = $p->ocultar_precio ? '' : number_format($p->precio, 2);

        // talles / colores (opcionales)
        $tallesUrl  = $p->talla_foto   ? asset('storage/'.$p->talla_foto)   : '';
        $coloresUrl = $p->colores_foto ? asset('storage/'.$p->colores_foto) : '';
      @endphp

      <button
        type="button"
        class="tile"
        data-index="{{ $i }}"
        data-nombre="{{ $p->nombre }}"
        data-precio="{{ $precio }}"
        data-desc="{{ trim(preg_replace('/\s+/', ' ', $p->descripcion ?? '')) }}"
        data-images='@json($arr)'
        data-talles="{{ $tallesUrl }}"
        data-colores="{{ $coloresUrl }}"
        aria-label="Ver {{ $p->nombre }}"
      >
        <img src="{{ $cover }}" alt="{{ $p->nombre }}" loading="lazy">
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
      <div class="price" id="mPriceWrap">$ <span id="mPrecio"></span></div>
      <p id="mDesc" style="opacity:.85"></p>
      <div id="extraBtns" style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">
        <button id="btnTalles" class="xbtn pill" style="display:none;">Ver talles</button>
        <button id="btnColores" class="xbtn pill" style="display:none;">Ver colores</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Lee todos los productos y sus imágenes
  const cards = Array.from(document.querySelectorAll('.tile'));
  const items = cards.map(c => ({
    nombre: c.dataset.nombre,
    precio: c.dataset.precio,   // podría venir vacío si está oculto
    desc:   c.dataset.desc,
    images: JSON.parse(c.dataset.images || '[]'),
    talles: c.dataset.talles || '',
    colores: c.dataset.colores || ''
  }));

  const backdrop = document.getElementById('modalBackdrop');
  const mNombre  = document.getElementById('mNombre');
  const mPrecio  = document.getElementById('mPrecio');
  const mPriceWrap = document.getElementById('mPriceWrap');
  const mDesc    = document.getElementById('mDesc');
  const mImg     = document.getElementById('mImg');
  const mClose   = document.getElementById('mClose');
  const mPrev    = document.getElementById('mPrev');
  const mNext    = document.getElementById('mNext');

  const btnTalles  = document.getElementById('btnTalles');
  const btnColores = document.getElementById('btnColores');

  let prod = 0;  // índice de producto
  let foto = 0;  // índice de foto dentro de ese producto

  function render(){
    const it = items[prod]; if(!it) return;
    const urls = it.images || [];
    mNombre.textContent = it.nombre || '';
    mDesc.textContent   = it.desc || '';

    // precio: mostrar u ocultar
    if (it.precio && it.precio.trim() !== '') {
      mPrecio.textContent = it.precio;
      mPriceWrap.style.display = '';
    } else {
      mPriceWrap.style.display = 'none';
    }

    // imagen
    mImg.src = urls[foto] || '';
    mImg.alt = it.nombre || '';

    // nav flechas
    const many = urls.length > 1;
    mPrev.style.display = many ? '' : 'none';
    mNext.style.display = many ? '' : 'none';

    // botones extra
    btnTalles.style.display  = it.talles  ? '' : 'none';
    btnColores.style.display = it.colores ? '' : 'none';
  }

  function openModal(pIdx){
    prod = pIdx;
    foto = 0;
    render();
    backdrop.classList.add('open');
    backdrop.setAttribute('aria-hidden', 'false');
    document.documentElement.style.overflow = 'hidden';
  }
  function closeModal(){
    backdrop.classList.remove('open');
    backdrop.setAttribute('aria-hidden', 'true');
    document.documentElement.style.overflow = '';
  }
  function next(){ const n = (items[prod].images || []).length; if (n) { foto = (foto + 1) % n; render(); } }
  function prev(){ const n = (items[prod].images || []).length; if (n) { foto = (foto - 1 + n) % n; render(); } }

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

  // Ver talles / colores = cambiar imagen del modal a esas URLs
  btnTalles?.addEventListener('click', () => {
    const it = items[prod];
    if (it?.talles) { mImg.src = it.talles; }
  });
  btnColores?.addEventListener('click', () => {
    const it = items[prod];
    if (it?.colores) { mImg.src = it.colores; }
  });
</script>

{{-- Botón flotante de WhatsApp (una sola vez) --}}
@php
  $waPhone = '5491166660040';
  $waText  = 'Hola, estuve viendo su sitio web. Quiero más información.';
  $waUrl   = 'https://api.whatsapp.com/send?phone='.$waPhone.'&text='.rawurlencode($waText);
@endphp

<a href="{{ $waUrl }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp">
  <svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true">
    <path fill="#fff" d="M17.472 14.382c-.297-.149-1.758-.867-2.027-.967-.27-.099-.466-.149-.663.15-.198.297-.761.967-.934 1.166-.2.2-.4.23-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.607.134-.133.297-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.663-1.6-.909-2.192-.24-.579-.487-.5-.663-.51l-.567-.01c-.198 0-.521.074-.793.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.1 3.204 5.077 4.487.709.306 1.262.489 1.694.625.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.412.248-.694.248-1.289.173-1.412-.074-.123-.272-.198-.57-.347m-5.421 7.403h-.003a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.284c0-5.45 4.436-9.884 9.888-9.884a9.86 9.86 0 016.993 2.897 9.823 9.823 0 012.893 6.997c-.003 5.45-4.437 9.884-9.889 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.717 1.463h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
  </svg>
</a>

<style>
  .whatsapp-float{
    position: fixed; right:18px; bottom:18px;
    width:56px; height:56px; border-radius:50%;
    background:#25D366; box-shadow:0 8px 20px rgba(0,0,0,.2);
    display:flex; align-items:center; justify-content:center;
    z-index:1060; text-decoration:none; transition:transform .15s, box-shadow .15s;
  }
  .whatsapp-float:hover{ transform:scale(1.05); box-shadow:0 10px 24px rgba(0,0,0,.25); }
  .whatsapp-float .wa-icon{ width:28px; height:28px; display:block; }
</style>

@include('layouts.footer')
