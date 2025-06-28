<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Responsive Movies Website</title>
    
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    
    <!-- Box Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <style>
        /* Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        /* Default Styling & Variables */
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
            scroll-padding-top: 2rem;
            scroll-behavior: smooth;
        }

        /* Variables for colors and settings */
        :root {
            --main-color: #ff2c1f;
            --text-color: #fff;
            --bg-color: #020307;
        }

        /* Custom scrollbar styling */
        html::-webkit-scrollbar {
            width: 0.5rem;
            background: transparent; /* Make scrollbar track transparent */
        }
        html::-webkit-scrollbar-thumb {
            background: var(--main-color);
            border-radius: 5rem;
        }

        body {
            background: var(--bg-color);
            color: var(--text-color);
        }

        section {
            padding: 4.5rem 0 1.5rem;
        }

        /* Header Styling */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 100px;
            transition: 0.5s;
        }
        /* Header style on scroll */
        header.shadow {
            background: var(--bg-color);
            box-shadow: 0 0 4px rgb(255 44 31 / 80%);
        }

        .logo {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-color);
            display: flex;
            align-items: center;
            column-gap: 0.5rem;
        }
        .logo .bx {
            font-size: 24px;
            color: var(--main-color);
        }

        .navbar {
            display: flex;
            column-gap: 3rem;
        }
        .navbar a {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
        }
        .navbar a:hover,
        .navbar .home-active {
            color: var(--main-color);
            border-bottom: 3px solid var(--main-color);
        }

        .btn {
            padding: 0.7rem 1.4rem;
            background: var(--main-color);
            color: var(--text-color);
            font-weight: 400;
            border-radius: 0.5rem;
        }
        .btn:hover {
            background: #fa1202;
            transform: scale(1.05);
            transition: 0.2s all linear;
        }
        #menu-icon {
            font-size: 2rem;
            cursor: pointer;
            display: none;
        }

        /* Home Section */
        .home {
            position: relative;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 5rem;
        }
        .home .swiper-slide {
            position: relative;
            width: 100%;
            height: 500px; /* Fixed height for slides */
        }
        .home img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .home .container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            padding-left: 100px;
        }
        .home-text {
            z-index: 100;
        }
        .home-text span {
            color: var(--main-color);
            font-size: 1rem;
            font-weight: 500;
        }
        .home-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin: 1rem 0;
        }
        .play {
            position: absolute;
            right: 10%;
            bottom: 10%;
            display: flex;
            align-items: center;
            column-gap: 0.5rem;
            color: var(--text-color);
        }
        .play .bx {
            background: var(--main-color);
            padding: 10px;
            font-size: 2rem;
            border-radius: 50%;
            border: 4px solid rgba(2, 3, 7, 0.4);
            color: var(--text-color);
        }
        .play .bx:hover {
            background: #fa1202;
            transition: 0.2s all linear;
        }

        /* Swiper Pagination */
        .swiper-pagination-bullet {
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            background: var(--text-color) !important;
            opacity: 1 !important;
        }
        .swiper-pagination-bullet-active {
            width: 25px !important;
            background: var(--main-color) !important;
            border-radius: 5px !important;
        }

        /* Reusable Heading */
        .heading {
            max-width: 968px;
            margin: 0 auto 2rem;
            font-size: 1.2rem;
            font-weight: 500;
            text-transform: uppercase;
            border-bottom: 1px solid var(--main-color);
        }

        /* Movies Section */
        .movies-container {
            max-width: 968px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, auto));
            gap: 1.5rem;
        }
        .box .box-img {
            width: 100%;
            height: 270px;
            overflow: hidden;
            border-radius: 0.5rem;
        }
        .box .box-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.2s all linear;
        }
        .box .box-img img:hover {
            transform: scale(1.1);
        }
        .box h3 {
            font-size: 1rem;
            font-weight: 500;
            margin-top: 1rem;
        }
        .box span {
            font-size: 13px;
            color: #b7b7b7;
        }

        /* Coming Soon Section */
        .coming-container {
            max-width: 968px;
            margin: 2rem auto 0;
        }
        .coming-container .box .box-img {
             height: 300px;
        }

        /* Newsletter Section */
        .newsletter {
            max-width: 968px;
            margin: 1rem auto 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            row-gap: 2rem;
            text-align: center;
        }
        .newsletter h2 {
            font-size: 1.4rem;
        }
        .newsletter form {
            background: #fff;
            padding: 10px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
        }
        .newsletter form .email {
            border: none;
            outline: none;
            background: transparent;
            color: #000;
            width: 200px;
        }
        .newsletter form .email::placeholder {
            color: #444;
        }
        .newsletter form .btn {
            text-transform: uppercase;
            font-weight: 500;
            cursor: pointer;
        }

        /* Footer Section */
        .footer {
            max-width: 968px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .social {
            display: flex;
            align-items: center;
            column-gap: 0.5rem;
        }
        .social .bx {
            background: #fff;
            padding: 10px;
            font-size: 1.2rem;
            border-radius: 50%;
            color: var(--main-color);
        }
        .social .bx:hover {
            background: var(--main-color);
            color: var(--text-color);
            transition: 0.2s all linear;
        }

        /* Copyright Section */
        .copyright {
            padding: 20px;
            text-align: center;
        }

        /* Responsive Design (Media Queries) */
        @media (max-width: 1080px) {
            header, .home .container, .heading, .movies-container, .coming-container, .newsletter, .footer {
                padding-left: 4%;
                padding-right: 4%;
            }
             .home .container {
                padding-left: 4rem;
            }
        }
        @media (max-width: 991px) {
            header {
                padding: 18px 4%;
            }
            section {
                padding: 50px 4%;
            }
            .home-text h1 {
                font-size: 2.5rem;
            }
        }
        @media (max-width: 768px) {
            header {
                padding: 12px 4%;
            }
            #menu-icon {
                display: initial;
            }
            .navbar {
                position: absolute;
                top: -570px;
                left: 0;
                right: 0;
                display: flex;
                flex-direction: column;
                background: var(--bg-color);
                row-gap: 1.4rem;
                padding: 20px;
                text-align: center;
                transition: 0.2s linear;
                border-top: 1px solid var(--main-color);
            }
            .navbar.active {
                top: 100%;
            }
            .btn {
                padding: 0.6rem 1.2rem;
            }
            .home {
                min-height: 440px;
                 margin-top: 4rem;
            }
            .home .swiper-slide {
                height: 440px;
            }
            .home-text h1 {
                font-size: 2.1rem;
            }
             .home .container {
                padding-left: 4%;
            }
            .movies-container {
                grid-template-columns: repeat(auto-fit, minmax(160px, auto));
            }
        }
        @media (max-width: 472px) {
            .newsletter form {
                width: 100%;
            }
             .newsletter form .email {
                width: 100%;
            }
            .footer {
                flex-direction: column;
                align-items: center;
                row-gap: 1.5rem;
            }
        }
        @media (max-width: 370px) {
            header {
                padding: 6px 4%;
            }
            .home {
                min-height: 380px;
            }
            .home .swiper-slide {
                height: 380px;
            }
            .home-text h1 {
                font-size: 1.7rem;
            }
            .play {
                right: 4%;
                bottom: 8%;
            }
            .play .bx {
                padding: 7px;
            }
            .movies-container {
                grid-template-columns: repeat(auto-fit, minmax(140px, auto));
                gap: 1rem;
            }
            .box .box-img {
                height: 240px;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="#home" class="logo">
            <i class='bx bxs-movie'></i>Movies
        </a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="#home" class="home-active">Home</a></li>
            <li><a href="#movies">Movies</a></li>
            <li><a href="#coming">Coming</a></li>
            <li><a href="#newsletter">Newsletter</a></li>
        </ul>
        <a href="#" class="btn">Sign In</a>
    </header>

    <section class="home swiper" id="home">
        <div class="swiper-wrapper">
            <div class="swiper-slide container">
                <img src="https://images.unsplash.com/photo-1620145648299-f926ac0a9470?q=80&w=1974&auto=format&fit=crop" alt="Movie Banner 1" onerror="this.onerror=null;this.src='https://placehold.co/1200x600/000/fff?text=Venom';">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Venom: Let There <br> Be Carnage</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class='bx bx-play'></i>
                    </a>
                </div>
            </div>
            <div class="swiper-slide container">
                <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=1925&auto=format&fit=crop" alt="Movie Banner 2" onerror="this.onerror=null;this.src='https://placehold.co/1200x600/000/fff?text=Avengers';">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Avengers: <br> Infinity War</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class='bx bx-play'></i>
                    </a>
                </div>
            </div>
            <div class="swiper-slide container">
                <img src="https://images.unsplash.com/photo-1608224522964-1100a066a9d7?q=80&w=2070&auto=format&fit=crop" alt="Movie Banner 3" onerror="this.onerror=null;this.src='https://placehold.co/1200x600/000/fff?text=Spider-Man';">
                <div class="home-text">
                    <span>Marvel Universe</span>
                    <h1>Spider-Man: <br> Far From Home</h1>
                    <a href="#" class="btn">Book Now</a>
                    <a href="#" class="play">
                        <i class='bx bx-play'></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </section>

    <section class="movies" id="movies">
        <h2 class="heading">Opening This Week</h2>
        <div class="movies-container">
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/2uNW4WbgBXL25BAbXGLnLqX71Sw.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Venom';">
                </div>
                <h3>Venom</h3>
                <span>120 min | Action</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/appMXfGG3EzwAP4sH7a3LhiS4T.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Dunkirk';">
                </div>
                <h3>Dunkirk</h3>
                <span>120 min | Adventure</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/2daC5DeXylpA5kU92U5jOwXlT1p.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Batman+v+Superman';">
                </div>
                <h3>Batman Vs Superman</h3>
                <span>120 min | Thriller</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/hXWBc0ioa5s942a42e5d5Hh3OKj.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=John+Wick+2';">
                </div>
                <h3>John Wick 2</h3>
                <span>120 min | Adventure</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/xLPff2OFyD4QW3aA20a05Xa4D29.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Aquaman';">
                </div>
                <h3>Aquaman</h3>
                <span>120 min | Action</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/uxzzxijgPIY7slzFvMotPv8wjA5.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Black+Panther';">
                </div>
                <h3>Black Panther</h3>
                <span>120 min | Thriller</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/prH27a0soB42nCjL8v4sPGKqA0F.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Thor';">
                </div>
                <h3>Thor</h3>
                <span>120 min | Adventure</span>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="https://image.tmdb.org/t/p/w500/c24sv2weTHPsmDa7jEMN0m2P3RT.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Bumblebee';">
                </div>
                <h3>Bumblebee</h3>
                <span>120 min | Thriller</span>
            </div>
        </div>
    </section>

    <section class="coming" id="coming">
        <h2 class="heading">Coming Soon</h2>
        <div class="coming-container swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="box-img">
                        <img src="https://image.tmdb.org/t/p/w500/eifGNCSDuxJblpc1mDj1OeoX6d8.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Justice+League';">
                    </div>
                    <h3>Justice League</h3>
                    <span>120 min | Action</span>
                </div>
                <div class="swiper-slide box">
                    <div class="box-img">
                        <img src="https://image.tmdb.org/t/p/w500/aqa5h5wzn0Gz9QAtiK343eASw9V.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Warcraft';">
                    </div>
                    <h3>Warcraft</h3>
                    <span>120 min | Adventure</span>
                </div>
                <div class="swiper-slide box">
                    <div class="box-img">
                        <img src="https://image.tmdb.org/t/p/w500/d7i9UXE1sT74P4j32B2IuI2t4cR.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Rampage';">
                    </div>
                    <h3>Rampage</h3>
                    <span>120 min | Thriller</span>
                </div>
                <div class="swiper-slide box">
                    <div class="box-img">
                        <img src="https://image.tmdb.org/t/p/w500/4PSaUNB7gnQ0Waa4oK52a2s4T5.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Black+Widow';">
                    </div>
                    <h3>Black Widow</h3>
                    <span>120 min | Action</span>
                </div>
                 <div class="swiper-slide box">
                    <div class="box-img">
                        <img src="https://image.tmdb.org/t/p/w500/7WsyChQLEftloC9zhUagmaGjPS.jpg" alt="" onerror="this.onerror=null;this.src='https://placehold.co/300x450/111/fff?text=Doctor+Strange';">
                    </div>
                    <h3>Doctor Strange</h3>
                    <span>120 min | Thriller</span>
                </div>
            </div>
        </div>
    </section>

    <section class="newsletter" id="newsletter">
        <h2>Subscribe To Get <br> Newsletter</h2>
        <form action="">
            <input type="email" class="email" placeholder="Enter Email..." required>
            <input type="submit" value="Subscribe" class="btn">
        </form>
    </section>

    <section class="footer">
        <a href="#home" class="logo">
            <i class='bx bxs-movie'></i>Movies
        </a>
        <div class="social">
            <a href="#"><i class='bx bxl-facebook'></i></a>
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-instagram'></i></a>
            <a href="#"><i class='bx bxl-tiktok'></i></a>
        </div>
    </section>

    <div class="copyright">
        <p>&#169; YourName All Right Reserved.</p>
    </div>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Swiper for the main Home section
        var homeSwiper = new Swiper(".home.swiper", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            loop: true,
        });

        // Swiper for the "Coming Soon" section
        var comingSwiper = new Swiper(".coming-container.swiper", {
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 5500,
                disableOnInteraction: false,
            },
            centeredSlides: true,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                568: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 4,
                },
                968: {
                    slidesPerView: 5,
                },
            },
        });
        
        // Mobile Menu Functionality
        let menu = document.querySelector('#menu-icon');
        let navbar = document.querySelector('.navbar');

        menu.onclick = () => {
            menu.classList.toggle('bx-x');
            navbar.classList.toggle('active');
        }

        window.onscroll = () => {
            menu.classList.remove('bx-x');
            navbar.classList.remove('active');
        }
        
        // Add Shadow to Header on Scroll
        let header = document.querySelector('header');
        
        window.addEventListener('scroll', () => {
            header.classList.toggle('shadow', window.scrollY > 0);
        });

    </script>
</body>
</html>
