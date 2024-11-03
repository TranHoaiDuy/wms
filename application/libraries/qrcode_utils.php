<?php
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Mpdf\Mpdf;

class QrCodeUtils
{

    public  function generateQrCode($data)
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<center><h1>Danh sách mã QR</h1></center>');

        // Thiết lập biến đếm để quản lý số lượng mã QR trên mỗi hàng
        $colCount = 0;

        // Bắt đầu bảng cho các mã QR
        $mpdf->WriteHTML('<table style="width: 100%; border-collapse: collapse;">');

        // Lặp qua các chuỗi string và tạo mã QR cho mỗi chuỗi
        foreach ($data as $index => $data) {
            // Tạo mã QR cho từng chuỗi
            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($data)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->build();

            // Lưu mã QR vào file tạm thời
            $qrImagePath = "qrcode_{$index}.png";
            $qrCode->saveToFile($qrImagePath);

            // Nếu là mã đầu tiên trên hàng mới, tạo dòng mới
            if ($colCount % 3 == 0) {
                $mpdf->WriteHTML('<tr>'); // Mở thẻ <tr> cho dòng mới
            }

            // Thêm mã QR vào ô trong bảng
            $mpdf->WriteHTML('<td style="text-align: center; vertical-align: top; padding: 10px; border: 1px solid black;">');
            $mpdf->WriteHTML('<h3 style="margin: 0;">' . htmlspecialchars($data) . '</h3>'); // Tiêu đề không có khoảng trống
            $qrCodeDataUri = $qrCode->getDataUri();
            $mpdf->WriteHTML('<img src="' . $qrCodeDataUri . '" />');
            $mpdf->WriteHTML('</td>');

            // Tăng biến đếm cột
            $colCount++;

            // Nếu đủ 3 mã trên 1 hàng, đóng hàng lại
            if ($colCount % 3 == 0) {
                $mpdf->WriteHTML('</tr>'); // Đóng thẻ <tr>
            }

            // Xóa file tạm sau khi đã thêm vào PDF
            unlink($qrImagePath);
        }

        // Đóng thẻ <table> nếu còn hàng chưa đóng
        if ($colCount % 3 != 0) {
            $mpdf->WriteHTML('</tr>');
        }
        $mpdf->WriteHTML('</table>');

        // Lưu file PDF vào bộ nhớ tạm và gửi cho người dùng tải xuống
        $mpdf->Output('qr_codes.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }
}
