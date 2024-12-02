<?php include "includes/header.php" ?>
<?php include "includes/navigation_other.php" ?>

  <div class="container">
    <div class="inner-container">
      <h1 style="margin-top: 2rem;">Admin Login</h1>
      <div class="inner-separator"></div>

      <section id="login" class="login section">
        <div class="container">
          <div class="row">
            <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
              <div class="login-form">
                <form action="includes/login.php" method="post">
                  <div class="row gy-4">
                    <div class="col-md-12">
                      <label for="username">Username</label>
                      <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required>
                    </div>

                    <div class="col-md-12">
                      <label for="password">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                    </div>

                    <div class="col-md-12 text-center">
                      <input type="submit" value="Login" name="login" class="btn btn-primary" />
                    </div>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

<?php include "includes/footer.php" ?>