<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2015-2018 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Core\Model;

/**
 * A Value for an article attribute.
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 */
class AtributoValor extends Base\ModelClass
{

    use Base\ModelTrait;

    /**
     * Code of the related attribute.
     *
     * @var string
     */
    public $codatributo;

    /**
     * Attribute name + value.
     *
     * @var string
     */
    public $descripcion;

    /**
     * Primary key
     *
     * @var int
     */
    public $id;

    /**
     * Value of the attribute
     *
     * @var string
     */
    public $valor;

    /**
     * This function is called when creating the model table. Returns the SQL
     * that will be executed after the creation of the table. Useful to insert values
     * default.
     *
     * @return string
     */
    public function install()
    {
        new Atributo();
        return parent::install();
    }

    /**
     * Returns the name of the column that is the model's primary key.
     *
     * @return string
     */
    public static function primaryColumn()
    {
        return 'id';
    }

    /**
     * Returns the name of the table that uses this model.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'atributos_valores';
    }

    /**
     * Check the delivery note data, return True if it is correct.
     *
     * @return bool
     */
    public function test()
    {
        $this->valor = $this->toolBox()->utils()->noHtml($this->valor);

        /// combine attribute name + value
        $attribute = new Atributo();
        if ($attribute->loadFromCode($this->codatributo)) {
            $this->descripcion = $attribute->nombre . ' ' . $this->valor;
        }

        return parent::test();
    }

    /**
     * 
     * @param string $type
     * @param string $list
     *
     * @return string
     */
    public function url(string $type = 'auto', string $list = 'ListAtributo')
    {
        $value = $this->codatributo;
        switch ($type) {
            case 'edit':
                return is_null($value) ? 'EditAtributo' : 'EditAtributo?code=' . $value;

            case 'list':
                return $list;

            case 'new':
                return 'EditAtributo';
        }

        /// default
        return empty($value) ? $list : 'EditAtributo?code=' . $value;
    }
}
