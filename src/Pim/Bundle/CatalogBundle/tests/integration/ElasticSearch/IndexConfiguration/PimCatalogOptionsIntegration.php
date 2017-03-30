<?php

namespace Pim\Bundle\CatalogBundle\tests\integration\ElasticSearch\IndexConfiguration;

/**
 * This integration tests checks that given an index configuration with multi options (string[]) values
 * the options research is consistent.
 *
 * @author    Anaël Chardan <anael.chardan@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class PimCatalogOptionsIntegration extends AbstractPimCatalogIntegration
{
    public function testInListOperatorWithOptionValue()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'terms' => [
                            'values.colors-options.<all_locales>.<all_channels>' => ['black', 'yellow'],
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults($query);

        $this->assertProducts(
            $productsFound,
            [
                'product_1',
                'product_2',
                'product_3',
                'product_6',
                'product_4',
                'product_5'
            ]
        );
    }

    public function testIsEmptyOperatorWithOptionValue()
    {
        $query = [
            'query' => [
                'bool' => [
                    'must_not' => [
                        'exists' => [
                            'field' => 'values.colors-options.<all_locales>.<all_channels>',
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults($query);

        $this->assertProducts($productsFound, ['product_7']);
    }

    public function testIsNotEmptyOperatorWithOptionValue()
    {
        $query = [
            'query' => [
                'bool' => [
                    'filter' => [
                        'exists' => [
                            'field' => 'values.colors-options.<all_locales>.<all_channels>',
                        ],
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults($query);

        $this->assertProducts($productsFound, ['product_1', 'product_2', 'product_3', 'product_4', 'product_5', 'product_6']);
    }

    public function testNotInListOperatorWithOptionValue()
    {
        $query = [
            'query' => [
                'bool' => [
                    'must_not' => [
                        'terms' => [
                            'values.colors-options.<all_locales>.<all_channels>' => ['black', 'blue'],
                        ],
                    ],
                    'filter' => [
                        'exists' => [
                            'field' => 'values.colors-options.<all_locales>.<all_channels>',
                        ],
                    ]
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults($query);

        $this->assertProducts($productsFound, ['product_6']);
    }

    public function testSortAscending()
    {
        $query = [
            'query' => [
                'match_all' => new \stdClass(),
            ],
            'sort'  => [
                [
                    'values.colors-options.<all_locales>.<all_channels>' => [
                        'order'   => 'asc',
                        'missing' => '_first',
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults($query);

        $this->assertSame(
            $productsFound,
            ['product_7', 'product_3', 'product_1', 'product_5', 'product_2', 'product_4', 'product_6']
        );
    }

    public function testSortDescending()
    {
        $query = [
            'query' => [
                'match_all' => new \stdClass(),
            ],
            'sort'  => [
                [
                    'values.colors-options.<all_locales>.<all_channels>' => [
                        'order'   => 'desc',
                        'missing' => '_last',
                    ],
                ],
            ],
        ];

        $productsFound = $this->getSearchQueryResults($query);

        $this->assertSame(
            $productsFound,
            ['product_2', 'product_4', 'product_6', 'product_5', 'product_3', 'product_1', 'product_7']
        );
    }

    /**
     * This method indexes dummy products in elastic search.
     */
    protected function addProducts()
    {
        $products = [
            [
                'identifier' => 'product_1',
                'values'     => [
                    'colors-options' => [
                        '<all_locales>' => [
                            '<all_channels>' => ['black'],
                        ],
                    ],
                ],
            ],
            [
                'identifier' => 'product_2',
                'values'     => [
                    'colors-options' => [
                        '<all_locales>' => [
                            '<all_channels>' => ['yellow', 'blue'],
                        ],
                    ],
                ],
            ],
            [
                'identifier' => 'product_3',
                'values'     => [
                    'colors-options' => [
                        '<all_locales>' => [
                            '<all_channels>' => ['black'],
                        ],
                    ],
                ],
            ],
            [
                'identifier' => 'product_4',
                'values'     => [
                    'colors-options' => [
                        '<all_locales>' => [
                            '<all_channels>' => ['blue', 'yellow'],
                        ],
                    ],
                ],
            ],
            [
                'identifier' => 'product_5',
                'values'     => [
                    'colors-options' => [
                        '<all_locales>' => [
                            '<all_channels>' => ['blue', 'black'],
                        ],
                    ],
                ],
            ],
            [
                'identifier' => 'product_6',
                'values'     => [
                    'colors-options' => [
                        '<all_locales>' => [
                            '<all_channels>' => ['yellow'],
                        ],
                    ],
                ],
            ],
            [
                'identifier' => 'product_7',
                'values'     => [],
            ],
        ];

        $this->indexProducts($products);
    }
}