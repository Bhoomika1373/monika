<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php include "includes/navigation_other.php" ?>
  <div class="container" id="imprint"></div>

  <script>
    if (localStorage.getItem('googtrans') === '/en/de/') {
      $('#imprint').html(`
        <div class="inner-container">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="content">
                  <h2 class="static-pages-heading" translate="no">Impressum</h2>
                  <br />
                  <p translate="no">
                    <strong translate="no">Name:</strong> Golden Arm Indisches Resturant<br />
                    <strong translate="no">Anschrift:</strong> Bruderstraße 58 59494 Soest<br />
                    <strong translate="no">Verantwortliche Person:</strong> Vinod Hadiya<br />
                    <strong translate="no">Steuer-ID:</strong> DE 312722508<br />
                    <strong translate="no">E-Mail:</strong> goldenarm59494@gmail.com
                  </p>

                  <br />
                  <h2 class="static-pages-heading" translate="no">Haftungsausschluss</h2>
                  <br />
                  <h3 translate="no">Haftung für Inhalte</h3>
                  <p translate="no">
                    Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. 1 Telemediengesetz (TMG) verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Rechtliche Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.
                  </p>

                  <br />
                  <br />

                  <h3 translate="no">Haftung für Links</h3>
                  <p translate="no">
                    Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
                  </p>

                  <br />
                  <br />

                  <h3 translate="no">Urheberrecht</h3>
                  <p translate="no">
                    Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.
                  </p>

                  <br />
                  <br />

                  <h3 translate="no">Verbraucherstreitbeilegung/Universelle Schlichtungsstelle</h3>
                  <p translate="no">
                    Wir sind nicht bereit oder verpflichtet, an einem Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.
                  </p>

                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
      `);
    } else {
      $('#imprint').html(`
        <div class="inner-container">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="content">
                  <h2 class="static-pages-heading">Imprint</h2>
                  <br />
                  <p>
                    <strong>Name:</strong> Golden Arm Indian Restaurant <br />
                    <strong>Address:</strong> Bruderstraße 58, 59494 Soest <br />
                    <strong>Responsible Person:</strong> Vinod Hadiya <br />
                    <strong>Tax ID:</strong> DE 312722508 <br />
                    <strong>Email:</strong> goldenarm59494@gmail.com
                  </p>

                  <br />
                  <h2 class="static-pages-heading">Disclaimer</h2>
                  <br />
                  <h3>Liability for Content</h3>
    <!--              <br />-->
                  <p>
                    As a service provider, we are responsible for our own content on these pages under general laws in accordance with § 7 paragraph 1 of the German Telemedia Act (TMG). However, according to §§ 8 to 10 TMG, we are not obligated to monitor transmitted or stored third-party information or to investigate circumstances indicating illegal activity. Obligations to remove or block the use of information under general law remain unaffected. Liability in this respect is, however, only possible from the point in time at which knowledge of a specific infringement is obtained. Upon becoming aware of such legal violations, we will remove the content in question immediately.
                  </p>

                  <br />
                  <br />

                  <h3>Liability for Links</h3>
    <!--              <div class="inner-separator"></div>-->
    <!--              <br />-->
                  <p>
                    Our website contains links to external third-party websites, the content of which we have no influence over. Therefore, we cannot accept any responsibility for this external content. The respective provider or operator of the linked pages is always responsible for their content. The linked pages were checked for possible legal violations at the time of linking. No unlawful content was apparent at the time of linking. Permanent monitoring of the linked pages’ content is, however, unreasonable without specific indications of a legal violation. Upon becoming aware of such legal violations, we will remove these links immediately.
                  </p>

                  <br />
                  <br />

                  <h3>Copyright</h3>
    <!--              <div class="inner-separator"></div>-->
    <!--              <br />-->
                  <p>
                    The content and works created by the site operators on these pages are subject to German copyright law. Reproduction, editing, distribution, and any type of use beyond the limits of copyright require the written consent of the respective author or creator. Downloads and copies of this page are permitted for private, non-commercial use only. Insofar as the content on this site was not created by the operator, the copyrights of third parties are respected. Third-party content is specifically marked as such. Should you nevertheless become aware of a copyright infringement, we request notification. Upon becoming aware of legal violations, we will remove such content immediately.
                  </p>

                  <br />
                  <br />

                  <h3>Consumer Dispute Resolution / Universal Arbitration Body</h3>
    <!--              <div class="inner-separator"></div>-->
    <!--              <br />-->
                  <p>
                    We are neither willing nor obligated to participate in dispute resolution proceedings before a consumer arbitration board.
                  </p>

                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
      `);
    }
  </script>
<?php include "includes/footer.php" ?>