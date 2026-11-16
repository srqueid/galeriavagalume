const fs = require('fs');
const p = 'wp-content/uploads/2026/11/';
const hero = fs.readFileSync(p + 'mockup-hero-banner-sm.jpg').toString('base64');
const c1 = fs.readFileSync(p + 'mockup-col-1-sm.jpg').toString('base64');
const c2 = fs.readFileSync(p + 'mockup-col-2-sm.jpg').toString('base64');
const c3 = fs.readFileSync(p + 'mockup-col-3-sm.jpg').toString('base64');
const teal = fs.readFileSync(p + 'mockup-teal-banner-sm.jpg').toString('base64');

const css = `body.home .site-header,body.home #masthead,body.home .site-footer,body.home #colophon{display:none!important}
body.home #primary,body.home #content,body.home .ast-container,body.home .site-content{padding:0!important;margin:0!important;max-width:100%!important;width:100%!important}
body.home .entry-content{margin:0!important;padding:0!important;max-width:100%!important}
:root{--gv-taupe:#ab8d7f;--gv-taupe-dark:#8e7164;--gv-sage:#b7c6b7;--gv-sage-dark:#374a38;--gv-teal:#04444d;--gv-gold:#d4b36a;--gv-dark:#222;--gv-green:#0b3829}
.gv-wrapper{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;color:var(--gv-dark);background:#fff;line-height:1.5;margin:0;padding:0;width:100%}
.gv-container{max-width:1140px;margin:0 auto;padding:0 16px}
.gv-top-bar{background:var(--gv-taupe);height:24px;width:100%}
.gv-header{background:#fff;padding:12px 16px;border-bottom:1px solid #ede8e5}
.gv-header-row{display:flex;align-items:center;justify-content:space-between;max-width:900px;margin:0 auto 10px auto}
.gv-btn-menu{background:var(--gv-taupe);color:#fff!important;font-weight:700;font-size:13px;letter-spacing:1px;padding:8px 20px;border:none;border-radius:4px;cursor:pointer;text-transform:uppercase;display:inline-flex;align-items:center;gap:6px}
.gv-btn-menu:hover{background:var(--gv-taupe-dark)}
.gv-logo-box{background:var(--gv-taupe);color:#fff!important;font-weight:700;font-size:15px;letter-spacing:2px;padding:6px 28px;border-radius:4px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}
.gv-search-wrap{max-width:900px;margin:0 auto}
.gv-search-form{display:flex;width:100%}
.gv-search-input{width:100%;background:var(--gv-taupe);border:none;border-radius:4px;padding:10px 16px;color:#fff;font-size:14px;font-style:italic;outline:none}
.gv-search-input::placeholder{color:rgba(255,255,255,0.85);font-style:italic}
.gv-mobile-drawer{display:none;background:#fff;border:1px solid #e0d7d2;border-radius:6px;margin:10px auto 0 auto;max-width:900px;padding:12px 16px;box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.gv-mobile-drawer.open{display:block}
.gv-mobile-drawer ul{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:8px}
.gv-mobile-drawer li a{color:var(--gv-dark);text-decoration:none;font-size:14px;padding:6px 10px;display:block}
.gv-hero-banner{position:relative;background:#f7f3f0;overflow:hidden;border-bottom:1px solid #e8e2de}
.gv-hero-container{position:relative;max-width:1140px;margin:0 auto;min-height:360px;display:flex;align-items:center}
.gv-hero-bg{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:1;opacity:.95}
.gv-hero-slide{position:relative;z-index:2;width:100%;padding:40px 20px 30px 20px}
.gv-hero-content{display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;max-width:1000px;margin:0 auto}
.gv-hero-left{max-width:480px;background:rgba(255,255,255,.9);backdrop-filter:blur(4px);padding:18px 22px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,.06);margin-bottom:15px}
.gv-hero-left h2{font-size:19px;line-height:1.4;color:#3b2a22;margin:0;font-weight:600}
.gv-hero-right{background:rgba(255,255,255,.92);backdrop-filter:blur(4px);padding:16px 22px;border-radius:8px;box-shadow:0 4px 15px rgba(0,0,0,.06);text-align:right;margin-bottom:15px}
.gv-hero-right .discount{font-size:30px;font-weight:800;color:#4a3429;line-height:1;margin:0 0 4px 0}
.gv-hero-right .discount span{font-size:15px;color:var(--gv-taupe-dark)}
.gv-hero-right .installments{font-size:13px;color:#555;margin:0 0 2px 0;font-weight:600}
.gv-hero-right .subtext{font-size:11px;color:#777;margin:0}
.gv-hero-watermark{position:relative;z-index:2;text-align:center;width:100%;margin-top:80px;font-size:22px;font-style:italic;font-weight:600;color:rgba(255,255,255,.95);text-shadow:0 2px 8px rgba(0,0,0,.6)}
.gv-dots{position:absolute;bottom:12px;left:0;width:100%;display:flex;justify-content:center;gap:8px;z-index:3}
.gv-dot{width:10px;height:10px;background:rgba(255,255,255,.6);border:1px solid var(--gv-taupe);border-radius:50%;cursor:pointer}
.gv-dot.active{background:var(--gv-taupe);transform:scale(1.2)}
.gv-differentials{background:#faf8f7;border-top:1px solid #eee8e5;border-bottom:1px solid #eee8e5;padding:16px 0}
.gv-diff-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;text-align:center}
.gv-diff-item{display:flex;flex-direction:column;align-items:center;gap:6px}
.gv-diff-icon svg{width:26px;height:26px;fill:#4a3830}
.gv-diff-text{font-size:11px;font-weight:600;color:#55443c;line-height:1.3;margin:0}
.gv-alma{padding:40px 16px 50px 16px;text-align:center;background:#fff}
.gv-badge{display:inline-block;background:var(--gv-sage);color:var(--gv-sage-dark);font-size:13px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:6px 20px;border-radius:20px;margin-bottom:16px}
.gv-alma-desc{max-width:680px;margin:0 auto 24px auto;font-size:14px;line-height:1.6;color:#444}
.gv-alma-subtitle{font-size:15px;font-weight:700;letter-spacing:1.5px;color:#222;text-transform:uppercase;margin-bottom:28px}
.gv-colls-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:900px;margin:0 auto}
.gv-coll-card{display:flex;flex-direction:column;align-items:center;text-decoration:none;color:inherit;transition:transform .2s}
.gv-coll-card:hover{transform:translateY(-4px)}
.gv-coll-img{width:100%;aspect-ratio:4/5;background:#f0ebe8;border-radius:4px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.08);margin-bottom:12px}
.gv-coll-img img{width:100%;height:100%;object-fit:cover;display:block}
.gv-coll-cap{font-size:13px;line-height:1.4;color:#333;font-weight:500;margin:0}
.gv-bestsellers{background:var(--gv-taupe);padding:40px 16px;color:#fff;text-align:center}
.gv-bestsellers-title{font-family:Georgia,serif;font-style:italic;font-size:26px;margin:0 0 24px 0;color:#fff;font-weight:400}
.gv-prods-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;max-width:960px;margin:0 auto}
.gv-prod-card{background:#fff;border-radius:6px;padding:16px;text-align:center;color:var(--gv-dark);box-shadow:0 4px 14px rgba(0,0,0,.12);text-decoration:none;display:flex;flex-direction:column;transition:transform .2s}
.gv-prod-card:hover{transform:translateY(-4px)}
.gv-prod-thumb{width:100%;height:200px;object-fit:cover;border-radius:4px;margin-bottom:12px;background:#f7f7f7}
.gv-prod-name{font-size:15px;font-weight:600;margin:0 0 6px 0;color:#222}
.gv-prod-price{font-size:16px;font-weight:700;color:var(--gv-taupe-dark);margin:0 0 12px 0}
.gv-btn-buy{background:var(--gv-taupe);color:#fff!important;padding:8px 16px;font-size:13px;font-weight:600;border-radius:4px;text-transform:uppercase;margin-top:auto}
.gv-conjuntos{background:var(--gv-taupe);padding:50px 16px;color:#fff;text-align:center;border-top:1px solid rgba(255,255,255,.15)}
.gv-conjuntos-title{font-family:Georgia,serif;font-style:italic;font-size:24px;margin:0 0 8px 0;color:#fff}
.gv-conjuntos-tag{font-size:32px;font-weight:800;letter-spacing:2px;color:rgba(255,255,255,.4);text-transform:uppercase;margin:8px 0;line-height:1.1}
.gv-conjuntos-sub{font-size:16px;font-style:italic;color:#f7ede8;margin:8px 0 16px 0}
.gv-conjuntos-note{font-size:13px;font-style:italic;color:#e5d3cb;margin:0}
.gv-why{background:var(--gv-teal);color:#fff;padding:50px 20px 40px 20px;overflow:hidden}
.gv-why-content{max-width:1000px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:30px}
.gv-why-left{max-width:440px}
.gv-why-sub{font-size:14px;letter-spacing:1px;text-transform:uppercase;margin:0}
.gv-why-title{font-size:38px;font-weight:800;letter-spacing:1px;margin:4px 0 6px 0;line-height:1.1}
.gv-why-right ul{list-style:none;margin:0;padding:0}
.gv-why-right li{font-size:12px;font-weight:700;letter-spacing:1px;color:var(--gv-gold);text-transform:uppercase;margin-bottom:12px;display:flex;align-items:center;gap:8px}
.gv-why-right li::before{content:'•';font-size:16px;color:var(--gv-gold)}
.gv-why-img{width:100%;max-width:800px;margin:30px auto 0 auto;display:block;border-radius:6px;box-shadow:0 8px 24px rgba(0,0,0,.3)}
.gv-footer{background:#fff;padding:50px 20px 30px 20px;color:var(--gv-dark);border-top:1px solid #eee}
.gv-footer-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:30px;max-width:1000px;margin:0 auto}
.gv-footer-col h4{font-size:14px;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin:0 0 16px 0}
.gv-footer-links{list-style:none;margin:0;padding:0}
.gv-footer-links li{margin-bottom:8px}
.gv-footer-links a{color:#444;text-decoration:none;font-style:italic;font-size:14px}
.gv-footer-links a:hover{color:var(--gv-taupe-dark)}
.gv-contact-list{list-style:none;margin:0;padding:0}
.gv-contact-list li{display:flex;align-items:center;gap:10px;font-size:14px;color:#444;margin-bottom:10px}
.gv-bullet{width:10px;height:10px;background:var(--gv-green);border-radius:50%;display:inline-block;flex-shrink:0}
.gv-payment-icons{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
.gv-badge-pay{background:var(--gv-green);color:#fff;font-size:10px;font-weight:700;padding:6px 12px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;min-width:44px;height:26px;text-transform:uppercase}
.gv-copy{text-align:center;font-size:12px;color:#888;margin-top:40px;border-top:1px solid #f0f0f0;padding-top:20px}
@media(max-width:768px){.gv-diff-grid{grid-template-columns:repeat(2,1fr);gap:16px}.gv-diff-item:last-child{grid-column:span 2}.gv-colls-grid{grid-template-columns:1fr;max-width:320px}.gv-footer-grid{grid-template-columns:1fr;gap:24px}.gv-why-content{flex-direction:column;text-align:center}.gv-why-right li{justify-content:center}.gv-hero-content{flex-direction:column;align-items:center}.gv-hero-left,.gv-hero-right{text-align:center;max-width:100%}.gv-hero-watermark{font-size:18px;margin-top:40px}}`;

const body = `<div class="gv-wrapper">
<div class="gv-top-bar"></div>
<header class="gv-header">
<div class="gv-header-row">
<button class="gv-btn-menu" onclick="document.getElementById('gvDrawer').classList.toggle('open')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg> MENU</button>
<a href="https://galeriavagalume.com.br/" class="gv-logo-box">LOGO</a>
</div>
<div class="gv-search-wrap">
<form class="gv-search-form" action="https://galeriavagalume.com.br/" method="get"><input class="gv-search-input" type="search" name="s" placeholder="espaço p pesquisa" autocomplete="off" /></form>
</div>
<div class="gv-mobile-drawer" id="gvDrawer">
<ul>
<li><a href="https://galeriavagalume.com.br/shop/">• Escolha por Categoria</a></li>
<li><a href="https://galeriavagalume.com.br/product-category/accessories/">• Fine-art</a></li>
<li><a href="https://galeriavagalume.com.br/product-category/bottoms/">• Fotografias</a></li>
<li><a href="https://galeriavagalume.com.br/product-category/dresses/">• Impressão em Canvas</a></li>
<li><a href="https://galeriavagalume.com.br/product-category/footwear/">• Edições Limitadas</a></li>
<li><a href="https://galeriavagalume.com.br/new-arrivals/">• O que tem de mais novo</a></li>
<li><a href="https://galeriavagalume.com.br/collection/">• Coleções</a></li>
<li><a href="https://galeriavagalume.com.br/about/">• Quem Somos</a></li>
<li><a href="https://galeriavagalume.com.br/contact/">• Fale Conosco</a></li>
</ul>
</div>
</header>
<section class="gv-hero-banner">
<div class="gv-hero-container">
<img class="gv-hero-bg" src="data:image/jpeg;base64,${hero}" alt="Galeria Vagalume Banner" />
<div class="gv-hero-slide">
<div class="gv-hero-content">
<div class="gv-hero-left"><h2>Transforme sua casa com estilo, conheça as coleções exclusivas da Galeria Vagalume</h2></div>
<div class="gv-hero-right"><p class="discount">10% OFF <span>no Pix</span></p><p class="installments">ou até em 10x sem juros</p><p class="subtext">na compra de qualquer quadro</p></div>
</div>
<div class="gv-hero-watermark">04 artes banners ( carrossel )</div>
</div>
<div class="gv-dots"><span class="gv-dot active"></span><span class="gv-dot"></span><span class="gv-dot"></span><span class="gv-dot"></span></div>
</div>
</section>
<section class="gv-differentials">
<div class="gv-container">
<div class="gv-diff-grid">
<div class="gv-diff-item"><div class="gv-diff-icon"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 14H6c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1z"/></svg></div><p class="gv-diff-text">Compre direto<br>da fábrica</p></div>
<div class="gv-diff-item"><div class="gv-diff-icon"><svg viewBox="0 0 24 24"><path d="M20 8h-3V4H1v13h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg></div><p class="gv-diff-text">Frete grátis compras<br>acima de R$ 199</p></div>
<div class="gv-diff-item"><div class="gv-diff-icon"><svg viewBox="0 0 24 24"><path d="M21 3H3C2 3 1 4 1 5v14c0 1 1 2 2 2h18c1 0 2-1 2-2V5c0-1-1-2-2-2zm0 16H3V5h18v14zm-10-7l-3 4h10l-4-5-3 3.5z"/></svg></div><p class="gv-diff-text">Molduras e papéis<br>de primeira qualidade</p></div>
<div class="gv-diff-item"><div class="gv-diff-icon"><svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 8.5L4.7 7 12 3.5 19.3 7 12 10.5zm-8 4.2V19l8 4 8-4v-4.3l-8 4-8-4z"/></svg></div><p class="gv-diff-text">Envio seguro assegurado<br>pelos correios</p></div>
<div class="gv-diff-item"><div class="gv-diff-icon"><svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg></div><p class="gv-diff-text">Satisfação<br>7 dias</p></div>
</div>
</div>
</section>
<section class="gv-alma">
<div class="gv-container">
<span class="gv-badge">ARTE COM ALMA BRASILEIRA</span>
<p class="gv-alma-desc">Nossas composições vão transformar seus ambientes com harmonia, conheça as artes exclusivas com a cara do Brasil criadas por nossos artistas além de grandes obras clássicas de pintores famosos de todo o mundo.</p>
<h3 class="gv-alma-subtitle">CONHEÇA NOSSAS COLEÇÕES</h3>
<div class="gv-colls-grid">
<a href="https://galeriavagalume.com.br/product-category/bottoms/" class="gv-coll-card">
<div class="gv-coll-img"><img src="data:image/jpeg;base64,${c1}" alt="Fotografias Exclusivas Assinadas" /></div>
<p class="gv-coll-cap">Mais de 100 fotografias exclusivas assinadas.</p>
</a>
<a href="https://galeriavagalume.com.br/product-category/dresses/" class="gv-coll-card">
<div class="gv-coll-img"><img src="data:image/jpeg;base64,${c2}" alt="Ilustrações Exclusivas Artistas Digitais" /></div>
<p class="gv-coll-cap">Ilustrações exclusivas criadas por nossos artistas digitais.</p>
</a>
<a href="https://galeriavagalume.com.br/product-category/accessories/" class="gv-coll-card">
<div class="gv-coll-img"><img src="data:image/jpeg;base64,${c3}" alt="Quadros de Pintores Famosos" /></div>
<p class="gv-coll-cap">Os maiores quadros de pintores famosos nas mais fiéis telas originais.</p>
</a>
</div>
</div>
</section>
<section class="gv-bestsellers">
<div class="gv-container">
<h2 class="gv-bestsellers-title">As mais vendidas</h2>
<div class="gv-prods-grid">
<div class="gv-prod-card">
<img class="gv-prod-thumb" src="https://galeriavagalume.com.br/wp-content/uploads/2025/11/quadro-na-sala.png" alt="Quadro Para Casa" />
<h3 class="gv-prod-name">Quadro Para Casa</h3>
<p class="gv-prod-price">R$ 1.512,00</p>
<a href="https://galeriavagalume.com.br/product/para-casa/" class="gv-btn-buy">Ver Detalhes</a>
</div>
<div class="gv-prod-card">
<img class="gv-prod-thumb" src="data:image/jpeg;base64,${c1}" alt="Série Crepúsculo Marítimo" />
<h3 class="gv-prod-name">Fotografia Crepúsculo</h3>
<p class="gv-prod-price">R$ 380,00</p>
<a href="https://galeriavagalume.com.br/shop/" class="gv-btn-buy">Ver Detalhes</a>
</div>
<div class="gv-prod-card">
<img class="gv-prod-thumb" src="data:image/jpeg;base64,${c2}" alt="Ilustração Alma Brasileira" />
<h3 class="gv-prod-name">Alma Brasileira</h3>
<p class="gv-prod-price">R$ 420,00</p>
<a href="https://galeriavagalume.com.br/shop/" class="gv-btn-buy">Ver Detalhes</a>
</div>
<div class="gv-prod-card">
<img class="gv-prod-thumb" src="data:image/jpeg;base64,${c3}" alt="O Nascimento de Vênus" />
<h3 class="gv-prod-name">Nascimento de Vênus</h3>
<p class="gv-prod-price">R$ 490,00</p>
<a href="https://galeriavagalume.com.br/shop/" class="gv-btn-buy">Ver Detalhes</a>
</div>
</div>
</div>
</section>
<section class="gv-conjuntos">
<div class="gv-container">
<h2 class="gv-conjuntos-title">Conjuntos de quadros</h2>
<div class="gv-conjuntos-tag">01 ARTE BANNER</div>
<p class="gv-conjuntos-sub">conheça nossas lindas sugestões de composição</p>
<p class="gv-conjuntos-note">esta informação estará no rodapé da pagina das coleções</p>
</div>
</section>
<section class="gv-why">
<div class="gv-why-content">
<div class="gv-why-left">
<p class="gv-why-sub">POR QUE ESCOLHER UM</p>
<h2 class="gv-why-title">QUADRO</h2>
<p class="gv-why-sub">DA GALERIA VAGALUME?</p>
</div>
<div class="gv-why-right">
<ul>
<li>ENTREGA SEGURA GARANTIDA PELOS CORREIOS</li>
<li>ALTA QUALIDADE NO PROCESSO DE PRODUÇÃO</li>
<li>CONSULTORIA GRATUITA</li>
<li>MOLDURAS</li>
</ul>
</div>
</div>
<img class="gv-why-img" src="data:image/jpeg;base64,${teal}" alt="Por que escolher Galeria Vagalume" />
</section>
<footer class="gv-footer">
<div class="gv-footer-grid">
<div class="gv-footer-col">
<ul class="gv-footer-links">
<li><a href="https://galeriavagalume.com.br/about/">Quem somos</a></li>
<li><a href="https://galeriavagalume.com.br/politica-de-devolucao/">Políticas de devolução</a></li>
<li><a href="https://galeriavagalume.com.br/politica-de-entrega/">Políticas de entrega</a></li>
</ul>
</div>
<div class="gv-footer-col">
<h4>ENTRE EM CONTATO</h4>
<ul class="gv-contact-list">
<li><span class="gv-bullet"></span> <a href="tel:61995528001" style="color:inherit;text-decoration:none;">61995528001</a></li>
<li><span class="gv-bullet"></span> <a href="https://wa.me/5561995528001" target="_blank" style="color:inherit;text-decoration:none;">(61) 99552 8001</a></li>
<li><span class="gv-bullet"></span> <a href="mailto:atendimento@galeriavagalume.com.br" style="color:inherit;text-decoration:none;">atendimento@galeriavagalume.com.br</a></li>
</ul>
</div>
<div class="gv-footer-col">
<h4>MEIOS DE PAGAMENTO</h4>
<div class="gv-payment-icons">
<span class="gv-badge-pay">PIX</span>
<span class="gv-badge-pay">VISA</span>
<span class="gv-badge-pay">MASTER</span>
<span class="gv-badge-pay">BOLETO</span>
<span class="gv-badge-pay">ELO</span>
</div>
</div>
</div>
<div class="gv-copy">© 2026 Galeria Vagalume. Todos os direitos reservados.</div>
</footer>
</div>`;

const fullHtml = '<!-- wp:html -->\n<style>\n' + css + '\n</style>\n' + body + '\n<!-- /wp:html -->';
fs.writeFileSync('compact_homepage.html', fullHtml, 'utf8');
console.log('Total characters of compact HTML:', fullHtml.length);
