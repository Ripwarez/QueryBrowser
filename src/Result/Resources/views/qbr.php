<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<style type="text/css">
    <?php echo file_get_contents(dirname(__FILE__) . '/../assets/css/qbr.css'); ?>
</style>

<script>
    <?php echo file_get_contents(dirname(__FILE__) . '/../assets/js/qbr.js'); ?>
</script>

<div
        class="qbr"
        id="<?php echo $id; ?>"
        data-id="<?php echo $id; ?>"
        data-page="<?php echo $currentPage; ?>"
        data-pagesize="<?php echo $pageSize; ?>"
        data-orderby="<?php echo $orderBy; ?>"
        data-orderdirection="<?php echo $orderDirection; ?>"
        <?php //data-globalsearch="<?php echo $globalSearch; ?>"
        data-form-action=""
        data-form-method="GET"
        data-use-ajax="0">

    <?php if (!empty($createURI)) : ?>
        <a href="<?php echo $createURI; ?>" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>
            Create</a>
    <?php endif; ?>

    <div class="pull-right">
        <?php /*<input type="text" name="qbr_q" class="form-control table-search" value="<?php echo $globalSearch; ?>" placeholder="Search">*/ ?>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <?php if (!empty($updateURI)) : ?>
                <th class="update"></th>
            <?php endif; ?>

            <?php foreach ($columns as $column) : ?>
                <?php if ($column->isVisible()) : ?>
                    <th>
                        <?php if ($column->isOrderable()) : ?>
                        <a href="javascript:void(0);" onclick="QBR.doSort('<?php echo $id; ?>', '<?php echo $column->getId(); ?>');" class="">
                            <?php echo $column->getName(); ?>

                            <?php if ($column->getOrderDirection() == 'desc') : ?>
                                <div class="pull-right sort_asc"></div>
                            <?php elseif ($column->getOrderDirection() == 'asc') : ?>
                                <div class="pull-right sort_desc"></div>
                            <?php else : ?>
                                <div class="pull-right sort"></div>
                            <?php endif ?>
                        <?php else : ?>
                                <?php echo $column->getName(); ?>
                        <?php endif; ?>
                        </a>
                    </th>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!empty($deleteURI)) : ?>
                <th class="delete"></th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $result) : ?>
            <tr>
                <?php if (!empty($deleteURI)) : ?>
                    <td class="update">
                        <a href="<?php echo $this->rewriteURIPlaceholders($updateURI, $result); ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                    </td>
                <?php endif; ?>

                <?php foreach ($columns as $column) : ?>
                    <?php if ($column->isVisible()) : ?>
                        <td>
                            <?php $value = $result[$column->getId()]; ?>
                            <?php if (is_array($value) || is_object($value)) : ?>
                                <?php $value = print_r($value, true); ?>
                            <?php endif; ?>

                            <?php if (isset($firstColumn) && $column == $firstColumn) : ?>
                                {{ HTML::link($this->rewriteURIPlaceholders($updateURI, $row), $value) }}
                            <?php elseif (!empty($globalSearch)) : ?>
                                <?php echo $this->highlightString($globalSearch, $value); ?>
                            <?php else : ?>
                                <?php echo $value; ?>
                            <?php endif; ?>
                        </td>
                    <?php endif ?>
                <?php endforeach; ?>

                <?php if (!empty($deleteURI)) : ?>
                    <td class="delete">
                        <a href="<?php echo $this->rewriteURIPlaceholders($deleteURI, $result); ?>"><span class="glyphicon glyphicon-remove"></span></a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1) : ?>
        <?php include('pagination.php'); ?>
    <?php endif; ?>
</div>
