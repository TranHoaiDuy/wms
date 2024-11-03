<?php

$id = '';
$stocktakingdtm    = '';
$note = '';
$stocktakingById = '';
$status = '';

if (!empty($inventoryInfo)) {
    foreach ($inventoryInfo as $gr) {
        $id = $gr->id;
        $stocktakingdtm = $gr->stocktakingdtm;
        $stocktakingById = $gr->stocktakingById;
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
    display: none;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Chi Tiết Phiếu Yêu Cầu
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

                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="ma-chung-tu">Kho Kiểm Kê</label>
                                            <input type="text" class="form-control" name="wms" id="wms"
                                                value="Kho Nguyên Vật Liệu" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Người Kiểm Kê</label>
                                            <select class="form-control" name="stocktakingById" required <?php if ($status > 0): echo 'disabled';
                                                                                                        endif; ?>>
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
                                                id="stocktakingdtm" required <?php if ($status > 0): echo 'readonly';
                                                                                endif; ?>
                                                value="<?php echo date('Y-m-d', strtotime($stocktakingdtm ?? '')); ?>">
                                            <span id="stocktakingdtm-error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Trạng Thái:</label>
                                            <input type="text" class="form-control" readonly value="<?php
                                                                                                    if ($status == 0) {
                                                                                                        echo "Đang Chờ Xác Nhận";
                                                                                                    } elseif ($status == 1) {
                                                                                                        echo "Chuẩn Bị Kiểm Kê";
                                                                                                    } elseif ($status == 2) {
                                                                                                        echo "Đã Kiểm Tra";
                                                                                                    } elseif ($status == 3) {
                                                                                                        echo "Đang Lưu Kho";
                                                                                                    } elseif ($status == 4) {
                                                                                                        echo "Hoàn Thành";
                                                                                                    }
                                                                                                    ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Biên Bản Kiểm Nghiệm:</label> <br />
                                            <a
                                                href="<?php echo base_url() ?>mom-reports/detail/<?php echo $id ?>"><?php echo htmlspecialchars($mom_code); ?></a>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label>Ghi Chú:</label>
                                        <textarea class="form-control" rows="5" <?php if ($status > 3): echo 'readonly';
                                                                                endif; ?>
                                            name="note"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                                    </div>

                            </form>
                        </div>


                        <?php if ($status == 0): ?>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select id="materials" name="materials" class="form-control select2">
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
                                    <th>Đơn Vị</th>
                                    <?php if ($status == 0): ?>
                                    <th>Hành Động</th>
                                    <?php endif; ?>
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
                                    <td><?php echo $material->unit; ?></td>
                                    <?php if ($status == 0): ?>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success save-material">
                                            <i class="fa fa-check-circle"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm remove-material">Xóa</button>
                                    </td>
                                    <?php endif; ?>
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
                                            <input type="hidden" class="material_quantityAvailable" value="${quantityAvailable}">
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
                        quantityAvailable = row.querySelector('.material_quantityAvailable').value;
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
                                material_quantityAvailable: quantityAvailable
                                // code: code
                            }
                        }).done(function(data) {
                            if (data.status = true) {
                                alert(success_mes);
                                $(row).attr('data-id', data.id);
                                currentRow.html('<i class="fa fa-check-circle"></i>');
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

                            if (confirm('Bạn có muốn xóa nguyên vật liệu chưa lưu này không?')) {
                                row.remove();
                                updateRowIndex();
                                selectedMaterials.delete(materialId);
                            }
                        } else {
                            if (confirm('Bạn có chắc chắn muốn xóa nguyên vật liệu này không?')) {
                                jQuery.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: hitURL,
                                    data: {
                                        id: id
                                    }
                                }).done(function(data) {
                                    if (data.status === true) {
                                        row.remove();
                                        updateRowIndex();
                                        selectedMaterials.delete(materialId);
                                    } else {
                                        alert('Xóa nguyên liệu thất bại');
                                    }
                                }).fail(function() {
                                    alert('Có lỗi xảy ra. Vui lòng thử lại.');

                                });
                            }
                        }
                    });
                });
                </script>


            </div>
        </div>
    </section>
</div>