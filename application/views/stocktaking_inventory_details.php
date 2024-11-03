<?php

$id = '';
$stocktakingdtm    = '';
$note = '';
$stocktakingById = '';
$status = '';
$createdDtm = '';
$createdBy = '';
if (!empty($inventoryInfo)) {
    foreach ($inventoryInfo as $gr) {
        $id = $gr->id;
        $stocktakingdtm = $gr->stocktakingdtm;
        $stocktakingById = $gr->stocktakingById;
        $note = $gr->note;
        $status = $gr->status;
        $createdDtm = $gr->createdDtm;
        $createdBy = $gr->createdBy;
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
        display: none;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Chi Tiết Phiếu Kiểm
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-tools">

                        <div class="box-body">
                            <input type="hidden" class="id" value="<?php echo $id; ?>">
                            <form id="receiptForm" action="<?php echo base_url('stocktaking/processForm/' . $id); ?>"
                                method="POST" onsubmit="return validateDates()">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <?php if ($status == 0): ?>
                                                <button type="submit" name="action" value="save"
                                                    class="btn btn-success btn-custom">
                                                    <i class="fa fa-save"></i> Lưu
                                                </button>
                                                <button type="submit" name="action" value="send-request"
                                                    class="btn btn-primary btn-custom">
                                                    <i class="fa fa-paper-plane"></i> Gởi Yêu Cầu
                                                </button>
                                            <?php elseif ($status == 1): ?>
                                                <button type="submit" name="action" value="start-audit"
                                                    class="btn btn-success btn-custom">
                                                    <i class="fa fa-check"></i> Tiến Hành Kiểm Kê
                                                </button>
                                            <?php elseif ($status == 2): ?>
                                                <button type="submit" name="action" value="complete-audit"
                                                    class="btn btn-success btn-custom">
                                                    <i class="fa fa-check-circle"></i> Hoàn Thành Kiểm Kê
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="ma-chung-tu">Ngày Tạo Phiếu</label>
                                            <input type="date" class="form-control"
                                                value="<?php echo date('Y-m-d', strtotime($createdDtm ?? '')); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ma-chung-tu">Kho Kiểm Kê</label>
                                            <input type="text" class="form-control" name="wms" id="wms"
                                                value="Kho Nguyên Vật Liệu" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Người Kiểm Kê</label>
                                            <select class="form-control" name="stocktakingById" disabled>
                                                <option value="">Chọn người kiểm kê</option>
                                                <?php foreach ($usersbyrole as $user): ?>
                                                    <option value="<?php echo htmlspecialchars($user->userId); ?>"
                                                        <?php echo (isset($stocktakingById) && $stocktakingById == $user->userId) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($user->name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Ngày Kiểm Kê</label>
                                            <input type="date" class="form-control" name="stocktakingdtm"
                                                id="stocktakingdtm" readonly
                                                value="<?php echo date('Y-m-d', strtotime($stocktakingdtm ?? '')); ?>">
                                            <span id="stocktakingdtm-error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="ma-chung-tu">Người Tạo Phiếu</label>
                                            <input type="text" class="form-control" name="wms" id="wms"
                                                value="<?php echo htmlspecialchars($createdBy); ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Trạng Thái:</label>
                                            <input type="text" class="form-control" readonly value="<?php
                                                                                                    if ($status == 0) {
                                                                                                        echo "Đang Chờ Xác Nhận";
                                                                                                    } elseif ($status == 1) {
                                                                                                        echo "Chuẩn Bị Kiểm Kê";
                                                                                                    } elseif ($status == 2) {
                                                                                                        echo "Đang Kiểm Kê";
                                                                                                    } elseif ($status == 3) {
                                                                                                        echo "Hoàn Thành";
                                                                                                    } ?>">
                                        </div>
                                    </div>
                                    <div class=" form-group">
                                        <label>Ghi Chú:</label>
                                        <textarea class="form-control" rows="5" readonly
                                            name="note"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                                    </div>
                            </form>
                            <?php if ($status == 2): ?>
                                <div class=" form-group row">
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
                                            <span class="input-group-btn" style="padding-left: 4px;">
                                                <button type="button" name="action" value="" id="search-qrcode"
                                                    class="btn btn-success">
                                                    <i class="fa fa-check"></i> Nhập
                                                </button>

                                            </span>
                                            <span class="input-group-btn" style="padding-left: 4px;">
                                                <button type="button" name="action" value="fake-data" id="fakeData" class="btn btn-warning">
                                                    <i class="fa fa-database"></i> Fake Data
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
                            <?php endif; ?>
                            <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <video id="preview" style="width: 100%; height: auto;"></video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php if ($status == 0): ?>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <select id="materials" name="materials" class="form-control select2" disabled>
                                        <option value="">Chọn nguyên vật liệu</option>
                                        <?php if (!empty($stocktaking)): ?>
                                            <?php foreach ($stocktaking as $mate): ?>
                                                <option value="<?php echo htmlspecialchars($mate['id']); ?>"
                                                    data-code="<?php echo htmlspecialchars($mate['materialCode']); ?>"
                                                    data-name="<?php echo htmlspecialchars($mate['materialName']); ?>"
                                                    data-unit="<?php echo htmlspecialchars($mate['unit']); ?>"
                                                    data-quantity="<?php echo htmlspecialchars($mate['quantity']); ?>"
                                                    data-quantityAvailable="<?php echo htmlspecialchars($mate['quantityAvailable']); ?>">

                                                    <?php echo htmlspecialchars($mate['materialCode'] . ' - ' . $mate['materialName']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">Không có nguyên vật liệu</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <table class="table table-bordered" id="selectedMaterialsTable">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã NVL</th>
                                    <th>Tên NVL</th>
                                    <th>Số Lượng Tồn Kho</th>
                                    <?php if ($status > 1): ?>
                                        <th>Số Lượng Thực Tại</th>
                                    <?php endif; ?>
                                    <th>Đơn Vị</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($inventoryItems)): ?>
                                    <?php foreach ($inventoryItems as $index => $material): ?>
                                        <tr data-id="<?php echo $material->id; ?>">
                                            <input type="hidden" class="material_id"
                                                value="<?php echo $material->materialId; ?>">
                                            <td><?php echo htmlspecialchars($index + 1); ?></td>
                                            <td><?php echo $material->materialCode; ?></td>
                                            <td><?php echo $material->materialName; ?></td>
                                            <td><?php echo $material->quantityAvailable; ?></td>
                                            <?php if ($status > 1): ?>
                                                <td><?php echo $material->realQuantity; ?></td>
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
                    function validateDates() {

                        var exportDtm = document.getElementById("stocktakingdtm").value;
                        var today = new Date().toISOString().split('T')[0];
                        document.getElementById("stocktakingdtm-error").innerText = "";

                        let isValid = true;
                        if (exportDtm < today) {
                            document.getElementById("stocktakingdtm-error").innerText =
                                "Ngày kiểm kê không được trước ngày hiện tại.";
                            isValid = false;
                        }

                        return isValid;
                    }
                </script>
                <script>
                    $(document).ready(function() {
                        let rowIndex = 1;
                        let selectedMaterials = new Set();
                        toggleSubmitButton();

                        function updateRowIndex() {
                            $('#selectedMaterialsTable tbody tr').each(function(index) {
                                console.log(index);
                                $(this).find('td:first').text(index + 1);
                            });
                        }

                        $('#materials').change(function() {
                            const selectedOption = $(this).find('option:selected');
                            const id = selectedOption.val();
                            const code = selectedOption.data('code');
                            const name = selectedOption.data('name');
                            const quantityAvailable = selectedOption.attr('data-quantityAvailable');
                            const unit = selectedOption.data('unit');
                            // const quantity = selectedOption.data('quantity');
                            if (id) {
                                if (!selectedMaterials.has(id)) {
                                    $('#selectedMaterialsTable tbody').append(`
                                        <tr data-id="0">
                                            <td>${rowIndex}</td>
                                            <td>${code}</td>
                                            <td>${name}</td>
                                            <input type="hidden" class="material_id" value="${id}">
                                            <td>${quantityAvailable}</td>
                                            <td>${unit}</td>CV 
                                           
                                            <td> <button  type="button" class="btn btn-sm btn-success save-material">
                                                <i class="fa fa-save"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm remove-material">Xóa</button></td>
                                        </tr>
                                    `);
                                    selectedMaterials.add(id);
                                    updateRowIndex();
                                    rowIndex++;
                                    toggleSubmitButton();
                                    $(this).val('').trigger('change');
                                } else {
                                    alert('Nguyên vật liệu này đã được chọn!');
                                }
                            }
                        });

                        jQuery(document).on("click", ".save-material", function() {
                            var row = (this).closest('tr');
                            var grId = document.querySelector('.id').value;
                            id = row.getAttribute('data-id');
                            hitURL = baseURL + "add-material-inventory";
                            materialId = row.querySelector('.material_id').value;
                            // quantity = row.querySelector('.material_quantity').value;
                            // code = row.querySelector('.material_code').value;

                            currentRow = $(this);
                            success_mes = 'Lưu nguyên vật này thành công';
                            error_mes = 'Nguyên vật đã tồn tại';
                            // if (id > 0) {
                            //     hitURL = baseURL + "edit-material";
                            //     success_mes = 'Cập nhât nguyên liệu thành công';
                            //     error_mes = 'Cập nhât nguyên liệu thành công';
                            // }

                            jQuery.ajax({
                                type: "POST",
                                dataType: "json",
                                url: hitURL,
                                data: {
                                    id: id,
                                    stocktakingId: grId,
                                    material_id: materialId,
                                    // material_quantity: quantity,
                                    // code: code
                                }
                            }).done(function(data) {
                                if (data.status = true) {
                                    alert(success_mes);
                                    $(row).attr('data-id', data.id);
                                    currentRow.html('<i class="fa fa-check-circle"></i>');
                                    toggleSubmitButton();
                                    // $('.process-storage').removeAttr("disabled")
                                } else if (data.status = false) {
                                    alert(error_mes);
                                } else {
                                    alert("Access denied..!");
                                }
                            }).fail(function(data) {
                                alert(error_mes);
                            })
                        });
                        jQuery(document).on('click', '.remove-material', function() {
                            const row = $(this).closest('tr');
                            const materialId = row.find('.material_id').val();
                            const id = row.data('id');
                            const hitURL = baseURL + "delete-material-inventory";


                            if (id === 0) {

                                if (confirm(
                                        'Bạn có muốn xóa nguyên vật liệu chưa lưu này không?')) {
                                    row.remove();
                                    updateRowIndex();
                                    selectedMaterials.delete(materialId);
                                    toggleSubmitButton();
                                }
                            } else {

                                jQuery.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: hitURL,
                                    data: {
                                        id: id
                                    }
                                }).done(function(data) {
                                    if (data.status === true) {
                                        alert('Xóa nguyên liệu thành công');
                                        row.remove();
                                        updateRowIndex();
                                        selectedMaterials.delete(materialId);
                                        toggleSubmitButton();
                                    } else {
                                        alert('Xóa nguyên liệu thất bại');
                                    }
                                }).fail(function() {
                                    alert('Có lỗi xảy ra. Vui lòng thử lại.');

                                });
                            }
                        });

                        function toggleSubmitButton() {
                            const submitButton = $('#createGoodsBtn');
                            if ($('#selectedMaterialsTable tbody tr').length > 0 && checkAllRowsSaved()) {
                                submitButton.prop('disabled', false);
                            } else {
                                submitButton.prop('disabled', true);
                            }
                        }

                        function checkAllRowsSaved() {
                            let allRowsSaved = true;
                            $('#selectedMaterialsTable tbody tr').each(function() {
                                if ($(this).attr('data-id') == "0") {
                                    allRowsSaved = false;
                                    return false;
                                }
                            });
                            return allRowsSaved;
                        }
                        $('#createGoodsBtn').on('click', function(event) {
                            if (!checkAllRowsSaved()) {
                                event.preventDefault();
                                // alert('Vui lòng lưu tất cả các nguyên vật liệu trước khi gửi!');
                            }
                        });
                    });
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
                                decodedContent =
                                    content; // Sử dụng nội dung gốc nếu không cần giải mã
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
                </script>
                <script type="text/javascript"
                    src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js">
                </script>
                <script type="text/javascript"
                    src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
            </div>
        </div>
    </section>
</div>