<style>
	a{
		font-weight: bold;
	}
</style>

<?php
	/* Check if required software is installed. Issue warning if not. */
 
	if (!$RequiredSoftwareHandler->isPHPCurlIsInstalled()){
		echo $RequiredSoftwareHandler->getNoCurlAdviceBasedOnOperatingSystem();
	}// end if

	if (!$RequiredSoftwareHandler->isPHPJSONIsInstalled()){
		echo $RequiredSoftwareHandler->getNoJSONAdviceBasedOnOperatingSystem();
	}// end if
?>
<link rel="stylesheet" href="styles/css/styles_index.css">

            <div class="row">
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>¿Qué tengo que hacer?</h2>
                            </a>
                          
                        </div>
                        <img src="images/interrogante.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>¡Ayúdame!</h2>
                            </a>
                         
                        </div>
                        <img src="images/Ayuda.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>Listado de vulnerabilidades</h2>
                            </a>
                          
                        </div>
                        <img src="images/Vulverabilidad.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>Anuncios de lanzamiento</h2>
                            </a>
                         
                        </div>
                        <img src="images/Ultima version.jpg" class="img-fluid">
                    </div>
                </div>
                               <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2> Ultima versión</h2>
                            </a>

                        </div>
                        <img src="images/Novedades.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>Consejos y guiones útiles</h2>
                            </a>

                        </div>
                        <img src="images/Consejo.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2> Archivo LDIF Mutillidae</h2>
                            </a>

                        </div>
                        <img src="images/Bases de d.jpg" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>¿Quiero ayudar?</h2>
                            </a>

                        </div>
                        <img src="images/Donacion.jpg" class="img-fluid">
                    </div>
                </div>
                               <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>Empezando!</h2>
                            </a>

                        </div>
                        <img src="images/Empezando.jpg" class="img-fluid">
                    </div>
                </div>
                  <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>Tutoriales en Video</h2>
                            </a>

                        </div>
                        <img src="images/Youtube.png" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="portfolio-container">
                        <div class="portfolio-details">
                            <a href="#">
                                <h2>Sugerencias y videos</h2>
                            </a>
                            <a href="#">
                                <p>Haga click para más información</p>
                            </a>
                        </div>
                        <img src="images/portfolio-02.jpg" class="img-fluid">
                    </div>
                </div>
               
                </div>
            </div>
            
        </div>
    </section>