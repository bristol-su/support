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

/* /reports/errors.html.twig */
class __TwigTemplate_6c967c5fda2286e38c6504b56f74a6b4496f334360b93e8d6c7cc4a37d94a51d extends \Twig\Template
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
        $this->parent = $this->loadTemplate("layout.html.twig", "/reports/errors.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        // line 4
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "title", []), "html", null, true);
        echo " &raquo; Compilation errors
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
        // line 13
        $context["errorCount"] = 0;
        // line 14
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? null), "files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 15
            echo "                    ";
            if (($this->getAttribute($this->getAttribute($context["file"], "allerrors", []), "count", []) > 0)) {
                // line 16
                echo "                        <li><a href=\"#";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "\"><i class=\"icon-file\"></i> ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "</a></li>
                    ";
            }
            // line 18
            echo "                    ";
            $context["errorCount"] = (($context["errorCount"] ?? null) + $this->getAttribute($this->getAttribute($context["file"], "allerrors", []), "count", []));
            // line 19
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 20
        echo "            </ul>
        </div>

        <div class=\"span10 offset2\">
            <ul class=\"breadcrumb\">
                <li><a href=\"";
        // line 25
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["/"]), "html", null, true);
        echo "\"><i class=\"icon-remove-sign\"></i></a><span class=\"divider\">\\</span></li>
                <li>Compilation Errors</li>
            </ul>

            ";
        // line 29
        if ((($context["errorCount"] ?? null) <= 0)) {
            // line 30
            echo "                <div class=\"alert alert-info\">No errors have been found in this project.</div>
            ";
        }
        // line 32
        echo "
            ";
        // line 33
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? null), "files", []));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 34
            echo "                <div class=\"package-contents\">
                    ";
            // line 35
            if (($this->getAttribute($this->getAttribute($context["file"], "allerrors", []), "count", []) > 0)) {
                // line 36
                echo "                        <a name=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "\" id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "\"></a>
                        <h3>
                            <i class=\"icon-file\"></i>
                            ";
                // line 39
                echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "path", []), "html", null, true);
                echo "
                            <small style=\"float: right;padding-right: 10px;\">";
                // line 40
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["file"], "allerrors", []), "count", []), "html", null, true);
                echo "</small>
                        </h3>
                        <div>
                            <table class=\"table markers table-bordered\">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Line</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                ";
                // line 52
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["file"], "allerrors", []));
                foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
                    // line 53
                    echo "                                    <tr>
                                        <td>";
                    // line 54
                    echo twig_escape_filter($this->env, $this->getAttribute($context["error"], "severity", []), "html", null, true);
                    echo "</td>
                                        <td>";
                    // line 55
                    echo twig_escape_filter($this->env, $this->getAttribute($context["error"], "line", []), "html", null, true);
                    echo "</td>
                                        <td>";
                    // line 56
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('trans')->getCallable(), [$this->getAttribute($context["error"], "code", []), $this->getAttribute($context["error"], "context", [])]), "html", null, true);
                    echo "</td>
                                    </tr>
                                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 59
                echo "                            </tbody>
                            </table>
                        </div>
                    ";
            }
            // line 63
            echo "                </div>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 65
        echo "        </div>
    </section>
";
    }

    public function getTemplateName()
    {
        return "/reports/errors.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  184 => 65,  177 => 63,  171 => 59,  162 => 56,  158 => 55,  154 => 54,  151 => 53,  147 => 52,  132 => 40,  128 => 39,  119 => 36,  117 => 35,  114 => 34,  110 => 33,  107 => 32,  103 => 30,  101 => 29,  94 => 25,  87 => 20,  81 => 19,  78 => 18,  70 => 16,  67 => 15,  62 => 14,  60 => 13,  53 => 8,  50 => 7,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/reports/errors.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//reports/errors.html.twig");
    }
}
