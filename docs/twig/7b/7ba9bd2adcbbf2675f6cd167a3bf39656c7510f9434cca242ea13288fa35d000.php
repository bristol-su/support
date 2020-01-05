<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* base/sidebar.html.twig */
class __TwigTemplate_7302b5ae2c596ac22000bc2c3cfcbb97124bdc8bc17850a9745ddf7a671c28c6 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'sidebar_buttons' => [$this, 'block_sidebar_buttons'],
            'sidebar_content' => [$this, 'block_sidebar_content'],
            'sidebar' => [$this, 'block_sidebar'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $this->displayBlock('sidebar_buttons', $context, $blocks);
        // line 2
        $this->displayBlock('sidebar_content', $context, $blocks);
        // line 3
        echo "
";
        // line 4
        $this->displayBlock('sidebar', $context, $blocks);
    }

    // line 1
    public function block_sidebar_buttons($context, array $blocks = [])
    {
    }

    // line 2
    public function block_sidebar_content($context, array $blocks = [])
    {
    }

    // line 4
    public function block_sidebar($context, array $blocks = [])
    {
        // line 5
        echo "    ";
        $this->displayBlock("sidebar_buttons", $context, $blocks);
        echo "
    ";
        // line 6
        $this->displayBlock("sidebar_content", $context, $blocks);
        echo "
";
    }

    public function getTemplateName()
    {
        return "base/sidebar.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  62 => 6,  57 => 5,  54 => 4,  49 => 2,  44 => 1,  40 => 4,  37 => 3,  35 => 2,  33 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "base/sidebar.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig/base/sidebar.html.twig");
    }
}
