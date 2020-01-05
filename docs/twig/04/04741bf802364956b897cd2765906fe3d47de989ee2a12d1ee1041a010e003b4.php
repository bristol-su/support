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

/* /graphs/class.html.twig */
class __TwigTemplate_cad98ab0cebcfa1c4d6a2c161d3502d910040fcea5d6a7e5aebc9b8ebb20d8d7 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'javascripts' => [$this, 'block_javascripts'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout.html.twig", "/graphs/class.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_stylesheets($context, array $blocks = [])
    {
        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["css/jquery.iviewer.css"]), "html", null, true);
        echo "\" rel=\"stylesheet\" media=\"all\"/>
    <style>
        #viewer {
            position: relative;
            width: 100%;
        }
        .wrapper {
            overflow: hidden;
        }
    </style>
";
    }

    // line 16
    public function block_javascripts($context, array $blocks = [])
    {
        // line 17
        echo "    <script src=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/jquery.mousewheel.js"]), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
    <script src=\"";
        // line 18
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/jquery.iviewer.js"]), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
    <script type=\"text/javascript\">
        \$(window).resize(function(){
            \$(\"#viewer\").height(\$(window).height() - 100);
        });

        \$(document).ready(function() {
            \$(\"#viewer\").iviewer({src: '";
        // line 25
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["graphs/classes.svg"]), "html", null, true);
        echo "', zoom_animation: false});
            \$('#viewer img').bind('dragstart', function(event){
                event.preventDefault();
            });
            \$(window).resize();
        });
    </script>
";
    }

    // line 34
    public function block_content($context, array $blocks = [])
    {
        // line 35
        echo "    <div class=\"row-fluid\">
        <div class=\"span12\">
            <div class=\"wrapper\">
                <div id=\"viewer\" class=\"viewer\"></div>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "/graphs/class.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  93 => 35,  90 => 34,  78 => 25,  68 => 18,  63 => 17,  60 => 16,  44 => 4,  41 => 3,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/graphs/class.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//graphs/class.html.twig");
    }
}
