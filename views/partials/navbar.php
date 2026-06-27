<?php $activeNav = $activeNav ?? ''; ?>
<div class="av-navbar-bars">
  <div class="av-bar-yellow"></div>
  <div class="av-bar-blue"></div>
  <div class="av-bar-red"></div>
  <nav class="av-navbar">
    <div class="container">
      <a class="av-navbar-brand" href="/">
        <div class="av-brand-mark"></div>
        <span class="av-brand-name">Apoya Venezuela</span>
      </a>
      <ul class="av-navbar-nav">
        <li>
          <a class="av-nav-link<?= $activeNav === 'centros' ? ' active' : '' ?>" href="/centros-acopio">
            <i class="bi bi-box-seam"></i>Centros
          </a>
        </li>
        <li>
          <a class="av-nav-link<?= $activeNav === 'refugios' ? ' active' : '' ?>" href="/refugios">
            <i class="bi bi-house-heart"></i>Refugios
          </a>
        </li>
        <li>
          <a class="av-nav-link<?= $activeNav === 'portales' ? ' active' : '' ?>" href="/portales">
            <i class="bi bi-globe2"></i>Portales
          </a>
        </li>
        <li>
          <a class="av-nav-link<?= $activeNav === 'voluntarios' ? ' active' : '' ?>" href="/voluntarios/lista">
            <i class="bi bi-people"></i>Voluntarios
          </a>
        </li>
        <li>
          <a class="av-nav-link<?= $activeNav === 'sugerencias' ? ' active' : '' ?>" href="/sugerencias">
            <i class="bi bi-chat-dots"></i>Sugerencias
          </a>
        </li>
      </ul>
    </div>
  </nav>
</div>
