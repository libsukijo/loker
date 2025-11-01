<div id="data_report">
    <table id="table" class="table table-bordered" cellspacing="0" style="min-width:800px">
        <thead>
            <tr>
                <td>Fakultas</td>
                <?php foreach ($data_peminjam as $value) {?>
                  <td><?php echo $value['fakultas'] ?></td>
               <?php }?>
               <td>Total</td>
            </tr>
            <tr>
                <td><?php echo $tanggal ?></td>
                <?php foreach ($data_peminjam as $value) {?>
                    <td><?php echo $value['jumlah'] ?></td>
                <?php }?>
                <td><?php echo $total ?></td>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>