<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <style>
    .background-box {
        background-image: url('https://www.whitecase.com/sites/default/files/styles/original_image/public/images/hero/2024/03/2024-real-estate-market-sentiment-survey-hero.jpg?itok=ggRx37lY'); /* Update with your image path */
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        /* opacity: 0.5;*/
    }

    .background-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(5, 4, 4, 0.4); /* Adjust the alpha for dimness */
        z-index: 1;
    }

    .background-box > * {
        position: relative;
        z-index: 2; /* Bring content above the dark overlay */
    }

    .auth-box {
        background-color: rgba(255, 255, 255, 0.9); /* White background with transparency */
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .auth-box h3 {
        margin-bottom: 20px;
        color: #333;
        text-align: center; /* Center the title */
    }

    .login-box{
        width: 400px;
    }

    .reg-box{
        width: 500px;
    }

    .panel-title{
        font-size: 2.4rem;
    }

    .btn-primary {
        background-color: #007bff; /* Bootstrap primary color */
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3; /* Darker shade on hover */
        border-color: #0056b3;
    }
</style>
    @yield('styles')
</head>

<body class="page-header-fixed">

    <!-- <div style="margin-top: 10%;"></div> -->
    <div class="background-box">
        <div class="container-fluid mx-0 px-0">
            @yield('content')
        </div>
    </div>

    <div class="scroll-to-top"
         style="display: none;">
        <i class="fa fa-arrow-up"></i>
    </div>

    @include('partials.javascripts')

</body>
</html>