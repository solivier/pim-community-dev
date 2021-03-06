<?php

namespace Pim\Bundle\ReferenceDataBundle\DataGrid\Extension\Sorter;

use Oro\Bundle\DataGridBundle\Datasource\DatasourceInterface;
use Pim\Bundle\DataGridBundle\Extension\Sorter\SorterInterface;

/**
 * Reference data sorter
 *
 * @author    Marie Bochu <marie.bochu@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ReferenceDataSorter implements SorterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(DatasourceInterface $dataSource, $field, $direction)
    {
        $dataSource->getProductQueryBuilder()->addSorter($field, $direction);
    }
}
