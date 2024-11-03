<script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>

<style>
    .content {
        background-color: #f8f9fa;
        position: relative;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      <i class="fa fa-tachometer" aria-hidden="true"></i> Quản Lý Mã Vạch
      </h1>
    </section>
    
    <section class="content">
        <div class="row">

        <video id="video" width="300" height="300"></video>
        <div id="result"></div>
            <!-- ./col -->
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>
    <script>
        const video = document.getElementById('video');
        const result = document.getElementById('result');

        // Setup camera stream
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                video.play();
                requestAnimationFrame(scanQRCode);
            });

        function scanQRCode() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);
                
                if (code) {
                    result.innerText = code.data;
                }
            }
            requestAnimationFrame(scanQRCode);
        }
    </script>