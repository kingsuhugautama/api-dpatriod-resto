<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 ,maximum-scale=1">
    <link rel="stylesheet" href="{{asset('css/callback.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>

<body class="content">
    <div class="container-fluid contennya">
        <div class="continer-md ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  d-flex justify-content-center">
                    <div class="boxheader mt-3 p-5">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                                <img src="{{asset('images/payment/logobayar.png')}}" class="img-fluid logonya" alt="">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                                <h2 class="labelpembayaran">Pembayaran Anda</h2>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                                <h1><b>Rp. {{$data['amount']}}<b></h2>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  d-flex justify-content-center">
                    <div class="boxcontent mt-1 p-5">
                        <div class="row mt-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-start">
                                <h2 class="DetailPembayaran"><b>Detail Pembayaran</b></h2>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>Tgl Pembayaran</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex text-end labelValue">
                                        <h4 class="text-end"><b>date ...</b></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>VA Number</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex text-end labelValue">
                                        <h4><b id="bankVa">{{$data['bankVacctNo']}}</b></h4>
                                        <span"><i id="copyButton" class="fa-solid fa-clone"></i></span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>Bank Name</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 d-flex text-end labelValue">
                                        <h4><b>{{$data['bankCd']}}</b></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>ID Order</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 items-end labelValue d-flex">
                                        <h4><b>{{$data['referenceNo']}}</b></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <h4>Description</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 items-end labelValue d-flex">
                                        <h4><b>{{$data['description']}}</b></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="notifContainer" style="display: none;">
                            <div class="notif">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-2">
                                        <p> Berhasil Copy Kode VA</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- <div class="row mt-1">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  d-flex justify-content-center">
                    <button class="Buttonbyr mt-3 p-5">
                        <h4><b>Lihat Cara Pembayaran...</b></h4>
                    </button>
                </div>
            </div> -->
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the copy button and input field elements
            const copyButton = document.getElementById('copyButton');
            const copyText = document.getElementById('bankVa');

            // Add a click event listener to the copy button
            copyButton.addEventListener('click', function() {
                const textarea = document.createElement('textarea');
                textarea.value = copyText.textContent;

                // Append the textarea element to the DOM
                document.body.appendChild(textarea);

                // Select the text inside the textarea
                textarea.select();

                // Copy the selected text to the clipboard
                const success = document.execCommand('copy');

                if (success) {
                    showToast()
                } else {
                    alert('gagal copy')
                }

                // Remove the textarea element from the DOM
                document.body.removeChild(textarea);

            });
        });
    </script>

    <script>
        function showToast() {
            const toast = document.getElementById('notifContainer')
            toast.style.display = 'flex';
            setInterval(() => {
                toast.style.display = 'none'
            }, 4000)

        }
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>