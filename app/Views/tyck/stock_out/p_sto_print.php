<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>仓储管理系统</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">
    <!-- <link rel="stylesheet" href="<?= base_url('/'); ?>/fonts/font.css"> -->
    <?= $this->renderSection('style') ?>
    <style>
        .page-break {
            page-break-before: always;
        }

        .report-invoice {
            font-size: 18px;
        }


        .table-print thead tr {
            border-style: solid;
        }

        .table-print td {
            border-style: solid;
            border-color: gray;
        }

        td {
            text-align: center;
            font-size: 15px;
        }

        .column {
            text-align: center;
            font-size: 15px;
        }

        .header {
            text-align: center;
        }

        @page {
            size: 11.0in 8.5in;
            margin: 5mm 10mm 5mm 10mm;
            /* change the margins as you want them to be. */
        }
    </style>
</head>

<body style="width: 11.0in;">
    <div class="wrapper m-0">
        <?php
        $row_count = count($rows);
        $max_row = 10;
        $page_num = ceil($row_count / $max_row);
        ?>

        <section class="invoice report-invoice">
            <!-- title row -->
            <?php for ($i = 1; $i <= $page_num; $i++) : ?>
                <div class="<?= ($i <> 1) ? 'd-none d-print-block page-break' : '' ?> pt-3">
                    <div class="row mb-1">
                        <div class="col-12">
                            <h2 class="row">
                                <div class="col">
                                    <p></p>
                                    <p class="brand-text h3 font-weight-bold header">BUKTI SERAH TERIMA NOTA GUDANG UMUM ELEKTRIK</p>
                                    <p class="brand-text h5 font-weight-bold header">电气通用仓库单据交接单</p>
                                    <button class="btn btn-primary d-print-none float-right" onclick="window.print()">
                                        <i class="fas fa-print"></i>
                                        打印
                                        Print
                                    </button>
                                </div>
                            </h2>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <?php if ($i == 1) : ?>
                    <div class="row d-print-none">
                        <div class="col">
                            <table class="table table-sm table-print">
                                <thead>
                                    <tr>
                                        <td class="font-weight-bold column">No.<br>库号</td>
                                        <td class="font-weight-bold column">Tanggal Terima<br>交接日期</td>
                                        <td class="font-weight-bold column">Perusahaan<br>公司</td>
                                        <td class="font-weight-bold column">Kode Barang<br>物料代码</td>
                                        <td class="font-weight-bold column">Nama Barang dan Spesifikasi<br>物料名称与规格型号</td>
                                        <td class="font-weight-bold column">Satuan<br>单位</td>
                                        <td class="font-weight-bold column">Jumlah<br>出库量</td>
                                        <td class="font-weight-bold column">Lokasi Barang<br>库位</td>
                                        <td class="font-weight-bold column">Perusahaan<br>领用公司</td>
                                        <td class="font-weight-bold column">Dept. Pengambilan<br>领用部门</td>
                                        <td class="font-weight-bold column">Diminta Oleh<br>领用人</td>
                                        <td class="font-weight-bold column">No. Nota<br>单据编号</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $l = 1;
                                    foreach ($rows as $row) : ?>
                                        <tr>
                                            <td><?= $l++; ?></td>
                                            <td><?= $row['sto_date']; ?></td>
                                            <td><?= $row['company']; ?></td>
                                            <td><?= $row['goods_id']; ?></td>
                                            <td><?= $row['name_type']; ?></td>
                                            <td><?= $row['unit']; ?></td>
                                            <td><?= $row['qty']; ?></td>
                                            <td><?= $row['location']; ?></td>
                                            <td><?= $row['recipient_company']; ?></td>
                                            <td><?= $row['recipient_dept']; ?></td>
                                            <td><?= $row['recipient_name']; ?></td>
                                            <td><?= $row['sto_id']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row d-none d-print-block">
                    <div class="col">
                        <table class="table table-sm table-print">
                            <thead>
                                <tr>
                                    <td class="font-weight-bold column">No<br>库号</td>
                                    <td class="font-weight-bold column">Tanggal Terima<br>交接日期</td>
                                    <td class="font-weight-bold column">Perusahaan<br>公司</td>
                                    <td class="font-weight-bold column">Kode Barang<br>物料代码</td>
                                    <td class="font-weight-bold column">Nama Barang dan Spesifikasi<br>物料名称与规格型号</td>
                                    <td class="font-weight-bold column">Satuan<br>单位</td>
                                    <td class="font-weight-bold column">Jumlah<br>出库量</td>
                                    <td class="font-weight-bold column">Lokasi Barang<br>库位</td>
                                    <td class="font-weight-bold column">Perusahaan<br>领用公司</td>
                                    <td class="font-weight-bold column">Dept. Pengambilan<br>领用部门</td>
                                    <td class="font-weight-bold column">Diminta Oleh<br>领用人</td>
                                    <td class="font-weight-bold column">No. Nota<br>单据编号</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $l = 1;
                                for ($j = 0; $j < ($page_num == $i ? (($row_count % $max_row == 0) ? $max_row : $row_count % $max_row) : $max_row); $j++) : ?>
                                    <tr>
                                        <td><?= $l++; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['sto_date']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['company']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['goods_id']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['name_type']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['unit']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['qty']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['location']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['recipient_company']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['recipient_dept']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['recipient_name']; ?></td>
                                        <td><?= $rows[$j + (($i - 1) * $max_row)]['sto_id']; ?></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="<?= ($i <> $page_num) ? 'd-none d-print-block' : '' ?>">
                    <div class="row mb-3">
                        <div class="col-3">
                            Yang Menyerahkan 交接人:
                        </div>
                        <div class="col"></div>
                        <div class="col-4">
                            Gudang Penerima 仓储部接收人:
                        </div>
                        <div class="col"></div>
                        <div class="col-3">
                            Statistika 统计员:
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </section>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>
    <!-- Moment.js -->
    <script src="<?= base_url(); ?>/plugins/moment/moment.min.js"></script>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>