<?php if (!defined('BASE_PATH')) exit('No direct script access allowed');

/**
 * Template Scanner
 **/
class TemplateScanner
{
    /**
     * The name of the template to scan.
     *
     * @var string
     * @access private
     */
    private $template_name;

    /**
     * The name of the class being generated.
     *
     * @var string
     * @access private
     */
    private $class_name;

    /**
     * The name of the class that will extend the generated class.
     * Example: CI_Controller
     *
     * @var string
     * @access private
     */
    private $parent_class;

    /**
     * The extra stuff that will be generated like actions and methods.
     *
     * @var string
     * @access private
     */
    private $extra;

    /**
     * The name of the php file that will be generated.
     *
     * @var string
     * @access private
     */
    private $filename;

    /**
     * The application folder. By default: "application".
     *
     * @var string
     * @access private
     */
    private $application_folder;

    /**
     * The constructor.
     *
     * @param string $template_name The template name without the extension.
     * @param array  $args All the extra info needed to genereate the files.
     * @access public
     * @return void
     * @author Aziz Light
     */
    public function __construct($template_name = "", array $args = array())
    {
      $this->template_name = $template_name;
      $this->set_attributes($args);
    }

    public function parse()
    {
        $template = $this->get_template();
        if ($template)
        {
            $patterns = array(
              "/{{class_name}}/",
              "/{{parent_class}}/",
              "/{{extra}}/",
              "/{{filename}}/",
              "/{{application_folder}}/",
            );

            // ugly
            $replacements = array(
              $this->class_name,
              $this->parent_class,
              $this->extra,
              $this->filename,
              $this->application_folder,
            );
            return preg_replace($patterns, $replacements, $template);
        }
    }

    /**
     * Get the contents of the template
     *
     * @access private
     * @return bool|string
     * @author Aziz Light
     */
    private function get_template()
    {
        $path = BASE_PATH . '/templates/' . $this->template_name . '.php';
        if (file_exists($path))
        {
            return file_get_contents($path);
        }
        else
        {
            return false;
        }
    }

    /**
     * Set the template attributes as properties.
     *
     * @param mixed $args : Array of attributes passed to the constructor.
     * @access private
     * @return void
     * @author Aziz Light
     */
    private function set_attributes(array $args)
    {
        $valid_attributes = array(
            "class_name"         => 'My' . ucfirst($this->template_name),
            "parent_class"       => 'CI_' . ucfirst($this->template_name),
            "extra"              => "",
            "filename"           => 'my_' . $this->template_name . '.php',
            "application_folder" => "application",
        );

        foreach ($valid_attributes as $valid_attribute => $default_value)
        {
            if (array_key_exists($valid_attribute, $args))
            {
                $this->$valid_attribute = $args[$valid_attribute];
            }
            else
            {
                $this->$valid_attribute = $default_value;
            }
        }
    }

}