<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        /* Write page CSS here*/
        .message-box {
            display: flex;
            justify-content: center;
            padding-top: 20vh;
            padding-bottom: 20vh;
        }

        .success-container {
            background: white;
            height: 480px;
            width: 90%;
            box-shadow: 5px 5px 10px grey;
            text-align: center;
        }

        .confirm-green-box {
            width: 100%;
            height: 140px;
            background: #d7f5da;
        }


        .monserrat-font {
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 2px;
        }





        /* --------------- site wide START ----------------- */
        .main {
            width: 80vw;
            margin: 0 10vw;
            height: 50vh;
            overflow: hidden;

        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        /*
        * Setting the site variables, example of how to use
        * color:var(--text-1);
        *
        */

        :root {
            --background-1: #ffffff;
            --background-2: #E3E3E3;
            --background-3: #A3CCC8;
            --text-1: #000000;
            --text-2: #ffffff;
            --text-size-reg: calc(20px + (20 - 18) * ((100vw - 300px) / (1600 - 300)));
            --text-size-sml: calc(10px + (10 - 8) * ((100vw - 300px) / (1600 - 300)));
        }

        .verticle-align {
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .no-style {
            padding: 0;
            margin: 0;
        }


        /* ------------------ site wide END ----------------- */

        /* ----- RESPONSIVE OPTIONS MUST STAY AT BOTTOM ----- */

        /* SM size and above unless over ridden in bigger sizes */
        @media (min-width: 576px) {
            /* sm size */

        }

        /* MD size and above unless over ridden in bigger sizes */
        @media (min-width: 768px) {}

        /* LG size and above unless over ridden in bigger sizes */
        @media (min-width: 992px) {}

        /* XL size and above */
        @media (min-width: 1200px) {}
    </style>
    <title>Wego Stores</title>
    <style>
        h1 {
            color: #333;
        }

        p {
            color: #555;
        }
    </style>
    <script>
        // Set a timer for the redirect
            const redirectUrl = "{{ $redirectUrl }}";
            const timer = {{ $timer }}; // Timer in seconds
    
            let countdown = timer;
            const interval = setInterval(() => {
                // Update the countdown display
                document.getElementById('timer').innerText = countdown;
                countdown--;
    
                if (countdown < 0) {
                    clearInterval(interval);
                    // Redirect to the target URL
                    window.location.href = redirectUrl;
                }
            }, 1000);
    </script>
</head>

<body>






    <!-- start of main -->
    <div style="background:">
        <div class="container">

            <div class="row">
                <div class="col-12 ">
                    <div class="message-box">
                        <div class="success-container">

                            <br>
                            <img src="https://scontent-lcy1-1.xx.fbcdn.net/v/t1.6435-9/31301640_2114242505489348_3921532491046846464_n.png?_nc_cat=104&ccb=1-3&_nc_sid=973b4a&_nc_ohc=pfOalMq8BzUAX-k-rhY&_nc_ht=scontent-lcy1-1.xx&oh=3af014dd12fa6e3d1816a3425a80e516&oe=609BE04A"
                                alt="" style="height: 100px;">
                            <br>
                            <div style="padding-left: 5%; padding-right: 5%">
                                <h3><i>Wego Stores</i></h3>
                                <hr>
                            </div>
                            <br>
                            <h1 class="monserrat-font" style="color: Grey">Thank you for your order</h1>
                            <br>

                            <div class="confirm-green-box">
                                <br>
                                <h5>ORDER CONFIRMATION BY {{ $payment->payment_method->name }}</h5>
                                <p>Your order #{{ $payment->transaction_id }} has been sucessful!</p>
                                <p>Thank you for choosing Wegostores . You will shortly receive a confirmation
                                    email.</p>



                            </div>

                            <br>
                            <small> Total Amount : {{ $totalAmount }}</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <section>
        <div class="container">
            <h1>{{ $message }}</h1>
            <p>You will be redirected in <span id="timer">{{ $timer }}</span> seconds...</p>
        </div>
    </section>
    <!-- end of main -->
</body>

</html>