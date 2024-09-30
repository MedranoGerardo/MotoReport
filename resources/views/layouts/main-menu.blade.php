<ul class="nav flex-column mt-4">
  <li class="nav-item mb-3">
    <a class="nav-link text-white {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
      <i class="fas fa-desktop"></i> Escritorio
    </a>
  </li>
  <li class="nav-item mb-3">
    <a class="nav-link text-white {{ Request::is('clientes') ? 'active' : '' }}" href="{{ route('clientes') }}">
      <i class="fas fa-users"></i> Clientes
    </a>
  </li>
  <li class="nav-item mb-3">
    <a class="nav-link text-white {{ Request::is('venta*') ? 'active' : '' }}" href="{{ route('ventas') }}">
      <i class="fas fa-shopping-cart"></i> Ventas
    </a>
  </li>
  @if (Auth::user()->hasRole('admin'))
  <li class="nav-item mb-3">
    <a class="nav-link text-white {{ Request::is('reportes') ? 'active' : '' }}" href="{{ route('reportes') }}">
      <i class="fas fa-chart-pie"></i> Reportes
    </a>
  </li>
  <li class="nav-item mb-3 dropdown">
    <a class="nav-link text-white {{ Request::is('configuracion*') ? 'active' : '' }} dropdown-toggle" href="#"
      id="configuracionDropdown" role="button" data-bs-toggle="dropdown"
      aria-expanded="{{ Request::is('configuracion*') ? 'true' : 'false' }}">
      <i class="fas fa-cogs"></i> Configuración
      <i
        class="float-end fas {{ Request::is('configuracion*') ? 'fa-caret-down' : 'fa-caret-left ' }} d-flex align-items-center"></i>
    </a>
    <ul class="dropdown-menu {{ Request::is('configuracion*') ? 'show' : '' }}" aria-labelledby="configuracionDropdown">
      <li>
        <a class="nav-link dropdown-item {{ Request::is('configuracion/usuarios') ? 'active' : '' }}"
          href="{{ route('users') }}">
          <i class="fas fa-users-cog"></i>
          Usuarios
        </a>
      </li>
      <li>
        <a class="nav-link dropdown-item {{ Request::is('configuracion/marcas-modelos') ? 'active' : '' }}"
          href="{{ route('marcas-modelos') }}">
          <i class="fas fa-motorcycle"></i>
          Marcas y Modelos
        </a>
      </li>
    </ul>
  </li>
  @endif
</ul>