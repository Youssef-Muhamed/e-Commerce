
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#app-nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link text-light "  href="dashboard.php"><?php echo lang('HOME') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="categories.php"><?php echo lang('CATEGORIES') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="items.php"><?php echo lang('ITEMS') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="members.php"><?php echo lang('MEMBERS') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="comments.php"><?php echo lang('COMMENTS') ?></a>
                </li>

            </ul>
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                <?php echo $_SESSION['Username'] ?>
                </a>
                <ul class="dropdown-menu" >
                    <li><a class="dropdown-item" href="../index.php">Visit Shope</a></li>
                    <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?> ">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>

</style>