<div class="content-wrapper">
    <section class="content-header">
        <h1 onclick="location.href='<?php echo base_url() ?>inventory';" style="cursor: pointer; ">
            Quản Lý Tồn Dư
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-purple">
                    <div class="box-header">
                        <h3 class="box-title">Danh Sách Chi Tiết</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên Nguyên Vật Liệu</th>
                                    <th>Khu</th>
                                    <th>Vị Trí</th>
                                    <th>Số lượng</th>
                                    <th>Đơn Vị</th>
                                    <th>QRCode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($inventoryDetails)): ?>
                                <?php foreach ($inventoryDetails as $in): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($in['materialName']); ?></td>
                                    <td><?php echo htmlspecialchars($in['cargo_area']); ?></td>
                                    <td><?php echo htmlspecialchars($in['cargo_location']); ?></td>
                                    <td><?php echo htmlspecialchars($in['total_quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($in['unit']); ?></td>
                                    <td><?php echo htmlspecialchars($in['qrcode_item']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Không có dữ liệu</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>