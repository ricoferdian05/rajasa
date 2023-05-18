<div class="container-fluid navbar-home border-0 shadow-lg">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <a href="<?= base_url(); ?>"><img src="<?= base_url('logo-navbar.png'); ?>" alt="Logo Rajasa Finishing" srcset="" width="150px" class="me-2"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link rounded-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Catalog
                                    </a>
                                    <ul class="dropdown-menu border-0 shadow">
                                        <li><a class="dropdown-item" href="#">Action</a></li>
                                        <li><a class="dropdown-item" href="#">Another action</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link rounded-3" href="#">Cart</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link rounded-3" href="#">Payment</a>
                                </li>
                                <li class="nav-item ms-2">
                                    <form class="d-flex" role="search">
                                        <div class="input-group">
                                            <input type="text" class="form-control search border-0" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <button class="input-group-text bg-white border-0" id="basic-addon2"><i class="bi bi-search"></i></button>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                            <div class="d-flex">
                                <?php if (!session()->get('isLogin')) { ?>
                                    <a href="<?= base_url('login'); ?>" class="btn btn-masuk">Login</a>
                                <?php } else { ?>
                                    <span class="name-account me-3">Rico Ferdian</span>
                                    <div class="dropstart">
                                        <a class="" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="https://api.dicebear.com/6.x/adventurer-neutral/svg?seed=Trouble" alt="avatar" class="avatar" />
                                        </a>
                                        <ul class="dropdown-menu border-0 shadow me-2">
                                            <li><a class="dropdown-item" href="#">Account</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

</div>