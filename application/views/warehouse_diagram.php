<style>
    .content img {
        display: block;
        /* max-width: 100%; */
        width: 1200px;
        height: 500px;
        /* margin: 0 auto; */
        object-fit: cover;
        position: relative;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Sơ Đồ Kho
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <img src="<?php echo base_url('images/sodotongquat.jpg') ?>" alt="Workplace" usemap="#workmap">
                                <map name="workmap">
                                    <!-- Tọa độ mới đã điều chỉnh -->
                                    <area alt="Kho Nguyên Vật Liệu" title="Kho Nguyên Vật Liệu"
                                        coords="376,136,536,93,621,110,621,151,604,176,449,194,381,178"
                                        shape="poly"
                                        href="<?php echo base_url('wmsLayout/view'); ?>">
                                </map>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>