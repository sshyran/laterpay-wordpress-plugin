<?php

/**
 * LaterPay bulk price form class.
 *
 * Plugin Name: LaterPay
 * Plugin URI: https://github.com/laterpay/laterpay-wordpress-plugin
 * Author URI: https://laterpay.net/
 */
class LaterPay_Form_BulkPrice extends LaterPay_Form_Abstract
{

    /**
     * Implementation of abstract method.
     *
     * @return void
     */
    public function init() {
        $currency = LaterPay_Helper_Config::get_currency_config();

        $this->set_field(
            'form',
            array(
                'validators' => array(
                    'is_string',
                    'cmp' => array(
                        array(
                            'like' => 'bulk_price_form',
                        ),
                    ),
                ),
            )
        );

        $this->set_field(
            'action',
            array(
                'validators' => array(
                    'is_string',
                    'cmp' => array(
                        array(
                            'eq' => 'laterpay_pricing',
                        ),
                    ),
                ),
            )
        );

        $this->set_field(
            '_wpnonce',
            array(
                'validators' => array(
                    'is_string',
                    'cmp' => array(
                        array(
                            'ne' => null,
                        ),
                    ),
                ),
            )
        );

        $this->set_field(
            'bulk_operation_id',
            array(
                'validators' => array(
                    'is_int',
                ),
                'filters'    => array(
                    'to_int',
                ),
                'can_be_null' => true,
            )
        );

        $this->set_field(
            'bulk_message',
            array(
                'validators' => array(
                    'is_string',
                ),
                'filters'    => array(
                    'to_string',
                ),
                'can_be_null' => true,
            )
        );

        $this->set_field(
            'bulk_action',
            array(
                'validators' => array(
                    'in_array' => array( 'set', 'increase', 'reduce', 'free', 'reset' ),
                    'depends'  => array(
                        array(
                            'field' => 'bulk_price',
                            'value' => 'set',
                            'conditions' => array(
                                'cmp' => array(
                                    array(
                                        'lte' => $currency['sis_max'],
                                        'gte' => $currency['ppu_min'],
                                    ),
                                    array(
                                        'eq'  => 0,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'filters' => array(
                    'to_string',
                ),
            )
        );

        $this->set_field(
            'bulk_selector',
            array(
                'validators' => array(
                    'in_array' => array( 'all', 'in_category' ),
                ),
                'filters' => array(
                    'to_string',
                ),
            )
        );

        $this->set_field(
            'bulk_category',
            array(
                'validators' => array(
                    'is_int'
                ),
                'filters' => array(
                    'to_int'
                ),
                'can_be_null' => true,
            )
        );

        $this->set_field(
            'bulk_category_with_price',
            array(
                'validators' => array(
                    'is_int',
                ),
                'filters' => array(
                    'to_int'
                ),
                'can_be_null' => true,
            )
        );

        $this->set_field(
            'bulk_price',
            array(
                'validators' => array(
                    'is_float',
                ),
                'filters' => array(
                    'delocalize',
                    'format_num' => array(
                        'decimals'      => 2,
                        'dec_sep'       => '.',
                        'thousands_sep' => ''
                    ),
                    'to_float'
                ),
                'can_be_null' => true,
            )
        );

        $this->set_field(
            'bulk_change_unit',
            array(
                'validators' => array(
                    'is_string',
                    'in_array' => array( 'EUR', 'USD', 'percent' ),
                ),
                'filters' => array(
                    'to_string',
                ),
                'can_be_null' => true,
            )
        );
    }
}
