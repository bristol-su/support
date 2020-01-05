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

/* layout.html.twig */
class __TwigTemplate_c29a7db621d2f0e6470275d3250963f191eacf521c83d7394462e727e7373e68 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $_trait_0 = $this->loadTemplate("elements/constant.html.twig", "layout.html.twig", 1);
        // line 1
        if (!$_trait_0->isTraitable()) {
            throw new RuntimeError('Template "'."elements/constant.html.twig".'" cannot be used as a trait.', 1, $this->getSourceContext());
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $_trait_1 = $this->loadTemplate("elements/property.html.twig", "layout.html.twig", 2);
        // line 2
        if (!$_trait_1->isTraitable()) {
            throw new RuntimeError('Template "'."elements/property.html.twig".'" cannot be used as a trait.', 2, $this->getSourceContext());
        }
        $_trait_1_blocks = $_trait_1->getBlocks();

        $_trait_2 = $this->loadTemplate("elements/method.html.twig", "layout.html.twig", 3);
        // line 3
        if (!$_trait_2->isTraitable()) {
            throw new RuntimeError('Template "'."elements/method.html.twig".'" cannot be used as a trait.', 3, $this->getSourceContext());
        }
        $_trait_2_blocks = $_trait_2->getBlocks();

        $_trait_3 = $this->loadTemplate("elements/namespaces.sidebar.html.twig", "layout.html.twig", 4);
        // line 4
        if (!$_trait_3->isTraitable()) {
            throw new RuntimeError('Template "'."elements/namespaces.sidebar.html.twig".'" cannot be used as a trait.', 4, $this->getSourceContext());
        }
        $_trait_3_blocks = $_trait_3->getBlocks();

        $this->traits = array_merge(
            $_trait_0_blocks,
            $_trait_1_blocks,
            $_trait_2_blocks,
            $_trait_3_blocks
        );

        $this->blocks = array_merge(
            $this->traits,
            [
                'title' => [$this, 'block_title'],
                'stylesheets' => [$this, 'block_stylesheets'],
                'javascripts' => [$this, 'block_javascripts'],
                'content' => [$this, 'block_content'],
            ]
        );
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 5
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\"/>
    <meta charset=\"utf-8\"/>
    <title>";
        // line 10
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
    <meta name=\"author\" content=\"\"/>
    <meta name=\"description\" content=\"\"/>

    <link href=\"";
        // line 14
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["css/bootstrap-combined.no-icons.min.css"]), "html", null, true);
        echo "\" rel=\"stylesheet\">
    <link href=\"";
        // line 15
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["css/font-awesome.min.css"]), "html", null, true);
        echo "\" rel=\"stylesheet\">
    <link href=\"";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["css/prism.css"]), "html", null, true);
        echo "\" rel=\"stylesheet\" media=\"all\"/>
    <link href=\"";
        // line 17
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["css/template.css"]), "html", null, true);
        echo "\" rel=\"stylesheet\" media=\"all\"/>
    ";
        // line 18
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 19
        echo "
    <!--[if lt IE 9]>
    <script src=\"";
        // line 21
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/html5.js"]), "html", null, true);
        echo "\"></script>
    <![endif]-->
    <script src=\"";
        // line 23
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/jquery-1.11.0.min.js"]), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 24
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/ui/1.10.4/jquery-ui.min.js"]), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 25
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/bootstrap.min.js"]), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 26
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/jquery.smooth-scroll.js"]), "html", null, true);
        echo "\"></script>
    <script src=\"";
        // line 27
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["js/prism.min.js"]), "html", null, true);
        echo "\"></script>
    <!-- TODO: Add http://jscrollpane.kelvinluck.com/ to style the scrollbars for browsers not using webkit-->
    ";
        // line 29
        $this->displayBlock('javascripts', $context, $blocks);
        // line 30
        echo "
    <link rel=\"shortcut icon\" href=\"";
        // line 31
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["images/favicon.ico"]), "html", null, true);
        echo "\"/>
    <link rel=\"apple-touch-icon\" href=\"";
        // line 32
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["images/apple-touch-icon.png"]), "html", null, true);
        echo "\"/>
    <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"";
        // line 33
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["images/apple-touch-icon-72x72.png"]), "html", null, true);
        echo "\"/>
    <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"";
        // line 34
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["images/apple-touch-icon-114x114.png"]), "html", null, true);
        echo "\"/>
</head>
<body>

<div class=\"navbar navbar-fixed-top\">
    <div class=\"navbar-inner\">
        <div class=\"container\">
            <a class=\"btn btn-navbar\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
                <i class=\"icon-ellipsis-vertical\"></i>
            </a>
            <a class=\"brand\" href=\"";
        // line 44
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["index.html"]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "name", []), "html", null, true);
        echo "</a>

            <div class=\"nav-collapse\">
                <ul class=\"nav pull-right\">
                    ";
        // line 48
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["project"] ?? null), "namespace", []), "children", [])) > 0)) {
            // line 49
            echo "                    <li class=\"dropdown\">
                        <a href=\"";
            // line 50
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["index.html"]), "html", null, true);
            echo "\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
                            API Documentation <b class=\"caret\"></b>
                        </a>
                        <ul class=\"dropdown-menu\">
                            ";
            // line 54
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["project"] ?? null), "namespace", []), "children", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
                // line 55
                echo "                            <li>";
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["namespace"]]);
                echo "</li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 57
            echo "                        </ul>
                    </li>
                    ";
        }
        // line 60
        echo "                    <li class=\"dropdown\" id=\"charts-menu\">
                        <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
                            Charts <b class=\"caret\"></b>
                        </a>
                        <ul class=\"dropdown-menu\">
                            <li>
                                <a href=\"";
        // line 66
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["graphs/class.html"]), "html", null, true);
        echo "\">
                                    <i class=\"icon-list-alt\"></i>&#160;Class hierarchy diagram
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class=\"dropdown\" id=\"reports-menu\">
                        <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
                            Reports <b class=\"caret\"></b>
                        </a>
                        <ul class=\"dropdown-menu\">
                            <li>
                                <a href=\"";
        // line 78
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["reports/errors.html"]), "html", null, true);
        echo "\">
                                    ";
        // line 79
        $context["errorCount"] = 0;
        // line 80
        echo "                                    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? null), "files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 81
            echo "                                        ";
            $context["errorCount"] = (($context["errorCount"] ?? null) + $this->getAttribute($this->getAttribute($context["file"], "allerrors", []), "count", []));
            // line 82
            echo "                                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 83
        echo "                                    <i class=\"icon-list-alt\"></i>&#160;Errors <span class=\"label label-info pull-right\">";
        echo twig_escape_filter($this->env, ($context["errorCount"] ?? null), "html", null, true);
        echo "</span>
                                </a>
                            </li>
                            <li>
                                <a href=\"";
        // line 87
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["reports/markers.html"]), "html", null, true);
        echo "\">
                                    ";
        // line 88
        $context["markerCount"] = 0;
        // line 89
        echo "                                    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? null), "files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 90
            echo "                                        ";
            $context["markerCount"] = (($context["markerCount"] ?? null) + $this->getAttribute($this->getAttribute($context["file"], "markers", []), "count", []));
            // line 91
            echo "                                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 92
        echo "                                    <i class=\"icon-list-alt\"></i>&#160;Markers <span class=\"label label-info pull-right\">";
        echo twig_escape_filter($this->env, ($context["markerCount"] ?? null), "html", null, true);
        echo "</span>
                                </a>
                            </li>
                            <li>
                                <a href=\"";
        // line 96
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["reports/deprecated.html"]), "html", null, true);
        echo "\">
                                    ";
        // line 97
        $context["deprecatedCount"] = 0;
        // line 98
        echo "                                    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "elements", []));
        foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
            if ($this->getAttribute($context["element"], "deprecated", [])) {
                // line 99
                echo "                                        ";
                $context["deprecatedCount"] = (($context["deprecatedCount"] ?? null) + $this->getAttribute($this->getAttribute($this->getAttribute($context["element"], "tags", []), "deprecated", []), "count", []));
                // line 100
                echo "                                    ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 101
        echo "                                    <i class=\"icon-list-alt\"></i>&#160;Deprecated <span class=\"label label-info pull-right\">";
        echo twig_escape_filter($this->env, ($context["deprecatedCount"] ?? null), "html", null, true);
        echo "</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--<div class=\"go_to_top\">-->
    <!--<a href=\"#___\" style=\"color: inherit\">Back to top&#160;&#160;<i class=\"icon-upload icon-white\"></i></a>-->
    <!--</div>-->
</div>

<div id=\"___\" class=\"container-fluid\">
    ";
        // line 116
        $this->displayBlock('content', $context, $blocks);
        // line 117
        echo "
    <footer class=\"row-fluid\">
        <section class=\"span10 offset2\">
            <section class=\"row-fluid\">
                <section class=\"span10 offset1\">
                    <section class=\"row-fluid footer-sections\">
                        <section class=\"span4\">
                            ";
        // line 125
        echo "                            <h1><i class=\"icon-code\"></i></h1>
                            <div>
                                <ul>
                                    ";
        // line 128
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["project"] ?? null), "namespace", []), "children", [])]));
        foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
            // line 129
            echo "                                        <li>";
            echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["namespace"]]);
            echo "</li>
                                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 131
        echo "                                </ul>
                            </div>
                        </section>
                        <section class=\"span4\">
                            ";
        // line 136
        echo "                            <h1><i class=\"icon-bar-chart\"></i></h1>
                            <div>
                                <ul>
                                    <li><a href=\"";
        // line 139
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["graphs/class.html"]), "html", null, true);
        echo "\">Class Hierarchy Diagram</a></li>
                                </ul>
                            </div>
                        </section>
                        <section class=\"span4\">
                            ";
        // line 145
        echo "                            <h1><i class=\"icon-pushpin\"></i></h1>
                            <div>
                                <ul>
                                    <li><a href=\"";
        // line 148
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["reports/errors.html"]), "html", null, true);
        echo "\">Errors</a></li>
                                    <li><a href=\"";
        // line 149
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["reports/markers.html"]), "html", null, true);
        echo "\">Markers</a></li>
                                </ul>
                            </div>
                        </section>
                    </section>
                </section>
            </section>
            <section class=\"row-fluid\">
                <section class=\"span10 offset1\">
                    <hr />
                    Documentation is powered by <a href=\"http://www.phpdoc.org/\">phpDocumentor </a> and authored
                    on ";
        // line 160
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "F jS, Y \\a\\t H:i"), "html", null, true);
        echo ".
                </section>
            </section>
        </section>
    </footer>
</div>

</body>
</html>
";
    }

    // line 10
    public function block_title($context, array $blocks = [])
    {
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "name", []), "html", null, true);
    }

    // line 18
    public function block_stylesheets($context, array $blocks = [])
    {
    }

    // line 29
    public function block_javascripts($context, array $blocks = [])
    {
    }

    // line 116
    public function block_content($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  424 => 116,  419 => 29,  414 => 18,  408 => 10,  394 => 160,  380 => 149,  376 => 148,  371 => 145,  363 => 139,  358 => 136,  352 => 131,  343 => 129,  339 => 128,  334 => 125,  325 => 117,  323 => 116,  304 => 101,  297 => 100,  294 => 99,  288 => 98,  286 => 97,  282 => 96,  274 => 92,  268 => 91,  265 => 90,  260 => 89,  258 => 88,  254 => 87,  246 => 83,  240 => 82,  237 => 81,  232 => 80,  230 => 79,  226 => 78,  211 => 66,  203 => 60,  198 => 57,  189 => 55,  185 => 54,  178 => 50,  175 => 49,  173 => 48,  164 => 44,  151 => 34,  147 => 33,  143 => 32,  139 => 31,  136 => 30,  134 => 29,  129 => 27,  125 => 26,  121 => 25,  117 => 24,  113 => 23,  108 => 21,  104 => 19,  102 => 18,  98 => 17,  94 => 16,  90 => 15,  86 => 14,  79 => 10,  72 => 5,  46 => 4,  39 => 3,  32 => 2,  25 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "layout.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean/layout.html.twig");
    }
}
