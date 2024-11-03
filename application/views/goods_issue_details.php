<?php

$id = '';
$code = '';
$re_code = '';
$factoryId =  '';
$exportDtm = '';
$createdBy = '';
$createdDtm = '';
$note = '';
$status = '';
if (!empty($goodsIssueInfo)) {
    foreach ($goodsIssueInfo as $gr) {
        $id = $gr->id;
        $code = $gr->code;
        $re_code = $gr->re_code;
        $factory_id = $gr->factoryId;
        $exportDtm = $gr->exportDtm;
        $createdBy = $gr->createdBy;
        $createdDtm = $gr->createdDtm;
        $note = $gr->note;
        $status = $gr->status;
    }
}

?>

<style>
.text-danger {
    color: red;
    font-size: 12px;
}

.bordered-card {
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 15px;
}

.btn-custom {
    margin-right: 10px;
}

.form-title {
    margin-bottom: 30px;
    font-weight: bold;
}

.detail-section {
    margin-bottom: 20px;
}

.table-wrapper {
    margin-top: 20px;
}

.error-message {
    color: red;
    /* display: none; */
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Chi Tiết Phiếu Xuất
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple ">
                    <div class="box-tools">
                        <div class="box-body">
                            <input type="hidden" class="id" value="<?php echo $id; ?>">
                            <form id="receiptForm" action="<?php echo base_url('issue/processForm/' . $id); ?>"
                                method="POST" onsubmit="return validateDates()">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <?php if ($status == 2): ?>
                                            <?php if($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_EMPLOYEE ) { ?>
                                            <button type="submit" name="action" value="export-goods"
                                                class="btn btn-success" id="prepareGoodsBtn">
                                                <i class="fa fa-check"></i> Xuất Hàng
                                            </button>
                                            <?php } ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="createBy">Mã Phiếu Xuất</label>
                                            <input type="text" class="form-control" name="code" id="code"
                                                value="<?php echo $code ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Phân Xưởng Yêu Cầu</label>
                                            <select class="form-control" name="factory_id" required disabled>
                                                <option value="">Chọn phân xưởng yêu cầu</option>
                                                <?php foreach ($factories as $fa): ?>
                                                <option value="<?php echo htmlspecialchars($fa->id); ?>"
                                                    <?php echo (isset($factory_id) && $factory_id == $fa->id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($fa->name); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Ngày Tạo Phiếu</label>
                                            <input type="text" class="form-control" name="createdDtm" readonly
                                                value="<?php echo htmlspecialchars($createdDtm ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createBy">Người Lập Phiếu</label>
                                            <input type="text" class="form-control" name="createBy" id="createBy"
                                                value="<?php echo $name ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Trạng Thái:</label>
                                            <input type="text" class="form-control" readonly value="<?php
                                                                                                    if ($status == 0) {
                                                                                                        echo "Đang Khởi Tạo";
                                                                                                    } elseif ($status == 1) {
                                                                                                        echo "Đang Xử Lý";
                                                                                                    } elseif ($status == 2) {
                                                                                                        echo "Đang Kiểm Hàng";
                                                                                                    } elseif ($status == 3) {
                                                                                                        echo "Đang Xuất";
                                                                                                    } elseif ($status == 4) {
                                                                                                        echo "Hoàn Thành";
                                                                                                    }
                                                                                                    ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Phiếu Nhận:</label> <br />
                                            <a
                                                href="<?php echo base_url() ?>receiving-request/detail/<?php echo $id ?>"><?php echo htmlspecialchars($re_code); ?></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi Chú:</label>
                                        <textarea class="form-control" rows="5" <?php if ($status > 0): echo 'readonly';
                                                                                endif; ?>
                                            name="note"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </form>
                            <?php if ($status == 2): ?>
                            <?php if($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_EMPLOYEE ) { ?>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type=" text" text-pac class="form-control"
                                            placeholder="Thông tin QRCode kiện hàng" name="input-qrcode" value="">
                                        <span class="input-group-btn">
                                            <button type="button" name="action" value="scan-qrcode" id="scanQRBtn"
                                                class="btn btn-primary" data-toggle="modal" data-target="#qrCodeModal">
                                                <i class="fa fa-qrcode"></i> Scan
                                            </button>
                                        </span>
                                        <span class="input-group-btn" style="
                                                padding-left: 4px;">
                                            <button type="button" name="action" value="" id="search-qrcode"
                                                class="btn btn-success">
                                                <i class="fa fa-check"></i> Nhập
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <p class="error-message" role="alert" id="qrcode-message">
                                    </p>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <video id="preview" style="width: 100%; height: auto;"></video>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php endif; ?>

                        <table class="table table-bordered" id="selectedMaterialsTable">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã Nguyên Vật Liệu</th>
                                    <th>Tên Nguyên Vật Liệu</th>
                                    <th>Số Lượng Kiện</th>
                                    <th>Số Lượng Yêu Cầu</th>
                                    <th>Số Lượng Xuất</th>
                                    <?php if ($status == 2): ?>
                                    <th>Vị Trí Gợi ý</th>
                                    <?php endif; ?>
                                    <th>Đơn Vị</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($goodsIssueItems)): ?>
                                <?php foreach ($goodsIssueItems as $index => $material): ?>
                                <tr data-id="<?php echo $material->id; ?>"
                                    id="material-<?php echo $material->materialCode; ?>">
                                    <input type="hidden" class="material_id"
                                        value="<?php echo $material->materialId; ?>">
                                    <td><?php echo htmlspecialchars($index + 1); ?></td>
                                    <td><?php echo $material->materialCode; ?></td>
                                    <td><?php echo $material->materialName; ?></td>
                                    <td> <input type="number" value="<?php echo $material->packing_quantity; ?>"
                                            class="form-control" readonly />
                                    </td>
                                    <td><input type="number" value="<?php echo $material->quantity; ?>"
                                            class="form-control quantity" readonly />
                                    </td>
                                    <!-- Khi scan xong hiện số lượng ở đây -->
                                    <td>
                                        <input type="number" value="<?php echo $material->realQuantity; ?>"
                                            class="form-control realQuantity" readonly />
                                    </td>
                                    <?php if ($status == 2): ?>
                                    <td>
                                        <!-- Xuất ds vị trí gợi ý -->
                                        <?php if (!empty($hintLocations[$material->materialId])): ?>
                                        <?php
                                                    $locations = array();
                                                    foreach ($hintLocations[$material->materialId] as $location):
                                                        array_push($locations, $location->cargo_area . $location->cargo_location);
                                                    //echo '<p>'.$location->cargo_area.$location->cargo_location."</p>";
                                                    endforeach;
                                                    echo join(", ", $locations);
                                                    ?>


                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td><?php echo $material->unit; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                jQuery(document).on("click", "#search-qrcode", function() {
                    document.getElementById("qrcode-message").innerText = "";
                    var grId = document.querySelector('.id').value;
                    var qrcode = document.querySelector('input[name="input-qrcode"]').value;
                    console.log(grId);
                    console.log(qrcode);
                    hitURL = baseURL + "scan-qrcode";

                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: hitURL,
                        data: {
                            id: grId,
                            qrcode: qrcode,
                        }
                    }).done(function(data) {
                        console.log(data);
                        if (data.status == true) {
                            var row = document.querySelector('#material-' + data.model.materialCode)
                                .closest('tr')
                            // let quantity = row.querySelector('.quantity').value;


                            row.querySelector('.realQuantity').value = data.model.realQuantity;
                            const prepareGoodsBtn = document.getElementById('prepareGoodsBtn');
                            let showbtnexp = true;
                            document.querySelectorAll('#selectedMaterialsTable tbody tr').forEach(
                                function(row) {
                                    // Lấy giá trị của quantity và realQuantity
                                    var quantityInput = row.querySelector('.quantity');
                                    var realQuantityInput = row.querySelector('.realQuantity');

                                    if (quantityInput && realQuantityInput) {
                                        var quantityValue = parseInt(quantityInput.value) ||
                                            0; // Nếu không có giá trị, gán là 0
                                        var realQuantityValue = parseInt(realQuantityInput.value) ||
                                            0; // Nếu không có giá trị, gán là 0
                                        console.log('Quantity and realQuantity are equal',
                                            quantityValue, realQuantityValue);
                                        // Kiểm tra xem quantity và realQuantity có bằng nhau không
                                        if (quantityValue == realQuantityValue) {

                                        } else {
                                            showbtnexp = false;

                                        }

                                    }
                                });
                            prepareGoodsBtn.disabled = !showbtnexp;
                            console.log(showbtnexp);
                        } else if (data.status == false) {
                            document.getElementById("qrcode-message").innerText = data.message;
                            //alert(error_mes);
                        } else {
                            alert("Access denied..!");
                        }
                    }).fail(function(data) {
                        //alert(error_mes);
                    })

                });

                function validateDates() {

                    var exportDtm = document.getElementById("exportDtm").value;
                    var today = new Date().toISOString().split('T')[0];
                    document.getElementById("exportDtm-error").innerText = "";

                    let isValid = true;
                    if (exportDtm <= today) {
                        document.getElementById("exportDtm-error").innerText =
                            "Ngày yêu cầu xuất phải sau ngày hiện tại.";
                        isValid = false;
                    }

                    return isValid;
                }
                </script>
                <script type="text/javascript">
                let scanner;
                $(document).ready(function() {
                    scanner = new Instascan.Scanner({
                        video: document.getElementById('preview')
                    });

                    // Bắt sự kiện khi có kết quả từ mã QR
                    scanner.addListener('scan', function(content) {
                        // Giải mã nội dung mã QR
                        let decodedContent;
                        try {
                            decodedContent = decodeURIComponent(escape(content));
                        } catch (e) {
                            decodedContent = content; // Sử dụng nội dung gốc nếu không cần giải mã
                            console.error('Error decoding content:', e);
                        }
                        $('#qrCodeModal').modal('hide');
                        $("input[name='input-qrcode']").val(decodedContent);
                    });

                    // Hiển thị camera khi modal mở
                    $('#qrCodeModal').on('shown.bs.modal', function() {
                        Instascan.Camera.getCameras().then(function(cameras) {
                            if (cameras.length > 0) {
                                scanner.start(cameras[0]);
                            } else {
                                alert('Không tìm thấy camera nào!');
                            }
                        }).catch(function(e) {
                            console.error(e);
                        });
                    });
                    // Dừng camera khi modal đóng
                    $('#qrCodeModal').on('hidden.bs.modal', function() {
                        if (scanner) {
                            scanner.stop();
                        }
                    });

                });
                const prepareGoodsBtn = document.getElementById('prepareGoodsBtn');
                let showbtnexp = true;
                document.querySelectorAll('#selectedMaterialsTable tbody tr').forEach(function(row) {
                    // Lấy giá trị của quantity và realQuantity
                    var quantityInput = row.querySelector('.quantity');
                    var realQuantityInput = row.querySelector('.realQuantity');

                    if (quantityInput && realQuantityInput) {
                        var quantityValue = parseInt(quantityInput.value) ||
                            0; // Nếu không có giá trị, gán là 0
                        var realQuantityValue = parseInt(realQuantityInput.value) ||
                            0; // Nếu không có giá trị, gán là 0
                        console.log('Quantity and realQuantity are equal', quantityValue, realQuantityValue);
                        // Kiểm tra xem quantity và realQuantity có bằng nhau không
                        if (quantityValue == realQuantityValue) {
                            // Ẩn hàng nếu bằng nhau

                        } else {
                            showbtnexp = false;
                        }
                    }
                });
                prepareGoodsBtn.disabled = !showbtnexp;
                console.log(showbtnexp);
                </script>
                <script type="text/javascript"
                    src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js">
                </script>
                <script type="text/javascript"
                    src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

            </div>
        </div>
</div>
</section>
</div>