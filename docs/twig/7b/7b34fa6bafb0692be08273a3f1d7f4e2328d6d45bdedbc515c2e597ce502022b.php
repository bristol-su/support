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

/* /reports/markers.html.twig */
class __TwigTemplate_0f8f1fa1b9d731a8786568768fe35a81f6efd346d55f855d98b4dbc5259c9d58 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'title' => [$this, 'block_title'],
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
        $this->parent = $this->loadTemplate("layout.html.twig", "/reports/markers.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        // line 4
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "title", []), "html", null, true);
        echo " &raquo; Markers
";
    }

    // line 7
    public function block_content($context, array $blocks = [])
    {
        // line 8
        echo "    <section class=\"row-fluid\">
        <div class=\"span2 sidebar\">
            <ul class=\"side-nav nav nav-list\">
                <li class=\"nav-header\">Navigation</li>
                ";
        // line 12
        $context["markerCount"] = 0;
        // line 13
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? null), "files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 14
            echo "                    ";
            if (($this->getAttribute($this->getAttribute($context["file"], "markers", []), "count", []) > 0)) {
                // line 15
                echo "                    <li><a href=\"#";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "\"><i class=\"icon-file\"></i> ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "</a></li>
                    ";
            }
            // line 17
            echo "                    ";
            $context["markerCount"] = (($context["markerCount"] ?? null) + $this->getAttribute($this->getAttribute($context["file"], "markers", []), "count", []));
            // line 18
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 19
        echo "            </ul>
        </div>

        <div class=\"span10 offset2\">

            <ul class=\"breadcrumb\">
                <li><a href=\"";
        // line 25
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["/"]), "html", null, true);
        echo "\"><i class=\"icon-map-marker\"></i></a><span class=\"divider\">\\</span></li>
                <li>Markers</li>
            </ul>

            ";
        // line 29
        if ((($context["markerCount"] ?? null) <= 0)) {
            // line 30
            echo "                <div class=\"alert alert-info\">No markers have been found in this project.</div>
            ";
        }
        // line 32
        echo "
            <div id=\"marker-accordion\">
                ";
        // line 34
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? null), "files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 35
            echo "                    ";
            if (($this->getAttribute($this->getAttribute($context["file"], "markers", []), "count", []) > 0)) {
                // line 36
                echo "                        <div class=\"package-contents\">
                            <a name=\"";
                // line 37
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "\" id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "\"></a>
                            <h3>
                            <i class=\"icon-file\"></i>
                                ";
                // line 40
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "
                                <small style=\"float: right;padding-right: 10px;\">";
                // line 41
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["file"], "markers", []), "count", []), "html", null, true);
                echo "</small>
                            </h3>
                            <div>
                                <table class=\"table markers table-bordered\">
                                    <tr>
                                        <th>Type</th>
                                        <th>Line</th>
                                        <th>Description</th>
                                    </tr>
                                    ";
                // line 50
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["file"], "markers", []));
                foreach ($context['_seq'] as $context["_key"] => $context["marker"]) {
                    // line 51
                    echo "                                        <tr>
                                            <td>";
                    // line 52
                    echo twig_escape_filter($this->env, $this->getAttribute($context["marker"], "type", []), "html", null, true);
                    echo "</td>
                                            <td>";
                    // line 53
                    echo twig_escape_filter($this->env, $this->getAttribute($context["marker"], "line", []), "html", null, true);
                    echo "</td>
                                            <td>";
                    // line 54
                    echo twig_escape_filter($this->env, $this->getAttribute($context["marker"], "message", []), "html", null, true);
                    echo "</td>
                                        </tr>
                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['marker'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 57
                echo "                                </table>
                            </div>
                        </div>
                    ";
            }
            // line 61
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 62
        echo "            </div>
        </div>
    </section>
";
    }

    public function getTemplateName()
    {
        return "/reports/markers.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  181 => 62,  175 => 61,  169 => 57,  160 => 54,  156 => 53,  152 => 52,  149 => 51,  145 => 50,  133 => 41,  129 => 40,  121 => 37,  118 => 36,  115 => 35,  111 => 34,  107 => 32,  103 => 30,  101 => 29,  94 => 25,  86 => 19,  80 => 18,  77 => 17,  69 => 15,  66 => 14,  61 => 13,  59 => 12,  53 => 8,  50 => 7,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/reports/markers.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//reports/markers.html.twig");
    }
}
