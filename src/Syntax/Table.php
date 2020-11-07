<?php
namespace Dateego\Generators\Syntax;

use Dateego\Generators\Compilers\TemplateCompiler;
use Dateego\Generators\Filesystem\Filesystem;

abstract class Table {

    /**
     * @var \Dateego\Generators\Filesystem\Filesystem
     */
    protected $file;

    /**
     * @var \Dateego\Generators\Compilers\TemplateCompiler
     */
    protected $compiler;

    /**
     * @param Filesystem $file
     * @param TemplateCompiler $compiler
     */
    function __construct(Filesystem $file, TemplateCompiler $compiler)
    {
        $this->compiler = $compiler;
        $this->file = $file;
    }

    /**
     * @param array  $fields
     * @param string $table
     * @param string $method
     * @param null   $connection
     *
     * @return string
     */
    public function run(array $fields, $table, $connection = null, $method = 'table')
    {
        $table = substr($table, strlen(\DB::getTablePrefix()));
        $this->table = $table;
        if (!is_null($connection)) $method = 'connection(\''.$connection.'\')->'.$method;
        $compiled = $this->compiler->compile($this->getTemplate(), ['table'=>$table,'method'=>$method]);
        return $this->replaceFieldsWith($this->getItems($fields), $compiled);
    }

    /**
     * Return string for adding all foreign keys
     *
     * @param array $items
     * @return array
     */
    protected function getItems(array $items)
    {
        $result = array();
        foreach($items as $item) {
            $result[] = $this->getItem($item);
        }
        return $result;
    }

    /**
     * @param array $item
     * @return string
     */
    abstract protected function getItem(array $item);

    /**
     * @param $decorators
     * @return string
     */
    protected function addDecorators($decorators)
    {
        $output = '';
        foreach ($decorators as $decorator) {
            $output .= sprintf("->%s", $decorator);
            // Do we need to tack on the parentheses?
            if (strpos($decorator, '(') === false) {
                $output .= '()';
            }
        }
        return $output;
    }


    /**
     * Fetch the template of the schema
     *
     * @return string
     */
    protected function getTemplate()
    {
        return $this->file->get(__DIR__.'/../templates/schema.txt');
    }


    /**
     * Replace $FIELDS$ in the given template
     * with the provided schema
     *
     * @param $schema
     * @param $template
     * @return mixed
     */
    protected function replaceFieldsWith($schema, $template)
    {
        return str_replace('$FIELDS$', implode(PHP_EOL."\t\t\t", $schema), $template);
    }

}