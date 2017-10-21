Showing <?php echo $firstResult; ?> to <?php echo $lastResult; ?> of <?php echo $totalResults; ?> results

<?php if (count($pageSizeOptions) > 0) : ?>
    Show
    <select name="pagesize" onchange="QBR.setPageSize('<?php echo $id; ?>', this.value);">
        <?php foreach ($pageSizeOptions as $i) : ?>
            <option value="<?php echo $i; ?>" <?php if ($pageSize == $i) {
                echo 'selected';
            } ?>><?php echo $i; ?></option>
        <?php endforeach; ?>
    </select>
    entries
<?php endif; ?>

<ul class="pagination">
    <?php if ($firstPage) : ?>
        <li>
            <a href="javascript:void(0);" onclick="QBR.gotoPage('<?php echo $id; ?>', <?php echo $firstPage; ?>);" title="First">&laquo;&laquo;</a>
        </li>
    <?php else : ?>
        <li class="disabled"><a href="javascript:void(0);">&laquo;&laquo;</a></li>
    <?php endif; ?>

    <?php if ($previousPage) : ?>
        <li><a href="javascript:void(0);" onclick="QBR.gotoPage('<?php echo $id; ?>', <?php echo $previousPage; ?>);">&laquo</a>
        </li>
    <?php else : ?>
        <li class="disabled"><a href="javascript:void(0);">&laquo</a></li>
    <?php endif; ?>

    <?php $prev = -1; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <?php if ($totalPages > 0) : ?>
            <?php if ($i <= 5 || ($i >= $currentPage - 2 && $i <= $currentPage + 2) || $i > $totalPages - 5) : ?>
                <?php if ($i > 1 && $i != $prev + 1) : ?>
                    <li class="disabled"><a href="javascript:void(0);">...</a></li>
                <?php endif; ?>
                <li class="<?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                    <a href="javascript:void(0);" onclick="QBR.gotoPage('<?php echo $id; ?>', <?php echo $i; ?>);"><?php echo $i; ?></a>
                </li>
                <?php $prev = $i; ?>
            <?php endif; ?>
        <?php else : ?>
            <li class="<?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                <a href="javascript:void(0);" onclick="QBR.gotoPage('<?php echo $id; ?>', <?php echo $i; ?>);"><?php echo $i; ?></a>
            </li>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($nextPage) : ?>
        <li><a href="javascript:void(0);" onclick="QBR.gotoPage('<?php echo $id; ?>', <?php echo $nextPage; ?>);">&raquo;</a>
        </li>
    <?php else : ?>
        <li class="disabled"><a href="javascript:void(0);">&raquo;</a></li>
    <?php endif; ?>

    <?php if ($lastPage) : ?>
        <li><a href="javascript:void(0);" onclick="QBR.gotoPage('<?php echo $id; ?>', <?php echo $lastPage; ?>);">&raquo;&raquo;</a>
        </li>
    <?php else : ?>
        <li class="disabled"><a href="javascript:void(0);">&raquo;&raquo;</a></li>
    <?php endif; ?>
</ul>
