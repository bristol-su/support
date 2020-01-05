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

/* /reports/deprecated.html.twig */
class __TwigTemplate_e3888d6e833185d3665af5f899a80edca02d1747cff933a994dce94e8078c0c6 extends \Twig\Template
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
        $this->parent = $this->loadTemplate("layout.html.twig", "/reports/deprecated.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        // line 4
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "title", []), "html", null, true);
        echo " &raquo; Deprecated elements
";
    }

    // line 7
    public function block_content($context, array $blocks = [])
    {
        // line 8
        echo "    <div class=\"row-fluid\">

        <div class=\"span2 sidebar\">
            <ul class=\"side-nav nav nav-list\">
                <li class=\"nav-header\">Navigation</li>
                ";
        // line 13
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "elements", []));
        foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
            if ($this->getAttribute($context["element"], "deprecated", [])) {
                // line 14
                echo "                    ";
                if (($this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []) != ($context["previousPath"] ?? null))) {
                    // line 15
                    echo "                        <li><a href=\"#";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []), "html", null, true);
                    echo "\"><i class=\"icon-file\"></i> ";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []), "html", null, true);
                    echo "</a></li>
                    ";
                }
                // line 17
                echo "                    ";
                $context["previousPath"] = $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []);
                // line 18
                echo "                ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 19
        echo "            </ul>
        </div>

        <div class=\"span10 offset2\">
            <ul class=\"breadcrumb\">
                <li><a href=\"";
        // line 24
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["/"]), "html", null, true);
        echo "\"><i class=\"icon-remove-sign\"></i></a><span class=\"divider\">\\</span></li>
                <li>Deprecated elements</li>
            </ul>

            <div id=\"marker-accordion\">
                ";
        // line 29
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["project"] ?? null), "indexes", []), "elements", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
            if ($this->getAttribute($context["element"], "deprecated", [])) {
                // line 30
                echo "                    ";
                if (($this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []) != ($context["previousPath"] ?? null))) {
                    // line 31
                    echo "                    ";
                    if (($context["previousPath"] ?? null)) {
                        // line 32
                        echo "                        </table>
                    </div>
                    ";
                    }
                    // line 35
                    echo "                    <a name=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []), "html", null, true);
                    echo "\" id=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []), "html", null, true);
                    echo "\"></a>
                    <h3>
                        <i class=\"icon-file\"></i>
                        ";
                    // line 38
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []), "html", null, true);
                    echo "
                        <small style=\"float: right;padding-right: 10px;\">";
                    // line 39
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($context["element"], "tags", []), "deprecated", []), "count", []), "html", null, true);
                    echo "</small>
                    </h3>
                    <div>
                        <table class=\"table markers table-bordered\">
                            <tr>
                                <th>Element</th>
                                <th>Line</th>
                                <th>Description</th>
                            </tr>
                    ";
                }
                // line 49
                echo "                            ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($context["element"], "tags", []), "deprecated", []));
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 50
                    echo "                                <tr>
                                    <td>";
                    // line 51
                    echo twig_escape_filter($this->env, $this->getAttribute($context["element"], "fullyQualifiedStructuralElementName", []), "html", null, true);
                    echo "</td>
                                    <td>";
                    // line 52
                    echo twig_escape_filter($this->env, $this->getAttribute($context["element"], "line", []), "html", null, true);
                    echo "</td>
                                    <td>";
                    // line 53
                    echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "description", []), "html", null, true);
                    echo "</td>
                                </tr>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 56
                echo "                    ";
                $context["previousPath"] = $this->getAttribute($this->getAttribute($context["element"], "file", []), "path", []);
                // line 57
                echo "                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 58
            echo "                    <div class=\"alert alert-info\">No deprecated elements have been found in this project.</div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 60
        echo "                    </table>
                </div>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "/reports/deprecated.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  185 => 60,  178 => 58,  172 => 57,  169 => 56,  160 => 53,  156 => 52,  152 => 51,  149 => 50,  144 => 49,  131 => 39,  127 => 38,  118 => 35,  113 => 32,  110 => 31,  107 => 30,  101 => 29,  93 => 24,  86 => 19,  79 => 18,  76 => 17,  68 => 15,  65 => 14,  60 => 13,  53 => 8,  50 => 7,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/reports/deprecated.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//reports/deprecated.html.twig");
    }
}
