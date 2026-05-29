<aside class="sidebar">

    <div class="sidebar-header">

        <div class="logo-icon">
            📚
        </div>

        <div class="logo-text">
            <h2>Biblioteca</h2>
            <span>Sistema de Gestión</span>
        </div>

    </div>

    <nav class="sidebar-menu">

        <ul>

            <li>
                <a href="?url=dashboard" class="<?= ($_GET['url'] ?? 'dashboard') == 'dashboard' ? 'active' : '' ?>">
                    <span>📊</span>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="?url=libros" class="<?= ($_GET['url'] ?? '') == 'libros' ? 'active' : '' ?>">
                    <span>📚</span>
                    Libros
                </a>
            </li>

            <li>
                <a href="?url=lectores" class="<?= ($_GET['url'] ?? '') == 'lectores' ? 'active' : '' ?>">
                    <span>👨‍🎓</span>
                    Lectores
                </a>
            </li>

            <li>
                <a href="?url=prestamos" class="<?= ($_GET['url'] ?? '') == 'prestamos' ? 'active' : '' ?>">
                    <span>🔄</span>
                    Préstamos
                </a>
            </li>
            <li>
                <a href="?url=inventario">
                    📦 Inventario
                </a>
            </li>

        </ul>

    </nav>

</aside>