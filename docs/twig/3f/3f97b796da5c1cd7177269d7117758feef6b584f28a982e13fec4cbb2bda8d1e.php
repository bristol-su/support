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

/* /namespace.html.twig */
class __TwigTemplate_2acdde639a6fc1ec0d87b8aac48eac3e0a756e4474d956d408fd8604f9b6da1f extends \Twig\Template
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
        $this->parent = $this->loadTemplate("layout.html.twig", "/namespace.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 96
    public function block_title($context, array $blocks = [])
    {
        // line 97
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "title", []), "html", null, true);
        echo " &raquo; ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "FullyQualifiedStructuralElementName", []), "html", null, true);
        echo "
";
    }

    // line 100
    public function block_content($context, array $blocks = [])
    {
        // line 101
        echo "    ";
        $context["self"] = $this;
        // line 102
        echo "
    <div class=\"row\">

        <div class=\"span4\">
            <div class=\"btn-group view pull-right\" data-toggle=\"buttons-radio\">
                <button class=\"btn details\" title=\"Show descriptions and method names\">
                    <i class=\"icon-list\"></i></button><button class=\"btn simple\" title=\"Show only method names\">
                    <i class=\"icon-align-justify\"></i>
                </button>
            </div>
            <ul class=\"side-nav nav nav-list\">
                <li class=\"nav-header\">
                    <i class=\"icon-map-marker\"></i> Namespaces
                </li>
                <a href=\"";
        // line 116
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["node"] ?? null)]), "html", null, true);
        echo "\" title=\"";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "\">
                    <i class=\"icon-th\"></i> ";
        // line 117
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "
                </a>
                <ul class=\"nav nav-list nav-namespaces\">
                    ";
        // line 120
        echo $context["self"]->getrenderNamespaceSidebar(($context["node"] ?? null));
        echo "
                </ul>
            </ul>
        </div>

        <div class=\"span8 namespace-contents\">
            ";
        // line 126
        echo $context["self"]->getrenderNamespaceDetails(($context["node"] ?? null));
        echo "
        </div>
    </div>
";
    }

    // line 3
    public function getelementSummary($__element__ = null, $__type__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "element" => $__element__,
            "type" => $__type__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 4
            echo "    <div id=\"";
            echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
            echo "_";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo "\" class=\"element ajax clickable ";
            echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
            echo "\" data-toggle=\"collapse\" data-target=\"#";
            echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
            echo "_";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo " .collapse\">
        <h1>";
            // line 5
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            if (call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)])) {
                echo "<a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
                echo "\">¶</a>";
            }
            echo "</h1>
        <p class=\"short_description\">";
            // line 6
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "summary", []), "html", null, true);
            echo "</p>
        <div class=\"details collapse\">
            ";
            // line 8
            if ((($context["type"] ?? null) == "function")) {
                // line 9
                echo "                ";
                $this->loadTemplate("method.html.twig", "/namespace.html.twig", 9)->display(twig_array_merge($context, ["method" => ($context["element"] ?? null)]));
                // line 10
                echo "            ";
            } else {
                // line 11
                echo "                ";
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["element"] ?? null), "description", [])]);
                echo "
            ";
            }
            // line 13
            echo "        </div>
        ";
            // line 14
            if (call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)])) {
                echo "<a href=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
                echo "\" class=\"more\">« More »</a>";
            }
            // line 15
            echo "    </div>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 18
    public function getbuildBreadcrumb($__element__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "element" => $__element__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 19
            echo "    ";
            $context["self"] = $this;
            // line 20
            echo "
    ";
            // line 21
            if (($this->getAttribute(($context["element"] ?? null), "parent", []) && ($this->getAttribute($this->getAttribute(($context["element"] ?? null), "parent", []), "name", []) != "\\"))) {
                // line 22
                echo "        ";
                echo $context["self"]->getbuildBreadcrumb($this->getAttribute(($context["element"] ?? null), "parent", []));
                echo "
    ";
            }
            // line 24
            echo "
    <li>
        <span class=\"divider\">\\</span><a href=\"";
            // line 26
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo "</a>
    </li>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 30
    public function getrenderNamespaceDetails($__node__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "node" => $__node__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 31
            echo "    ";
            $context["self"] = $this;
            // line 32
            echo "
    ";
            // line 33
            if ((((((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "classes", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "traits", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "functions", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0))) {
                // line 34
                echo "
        <ul class=\"breadcrumb\">
            <li><a href=\"";
                // line 36
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["index.html"]), "html", null, true);
                echo "\"><i class=\"icon-th\"></i></a></li>
            ";
                // line 37
                echo $context["self"]->getbuildBreadcrumb(($context["node"] ?? null));
                echo "
        </ul>

        ";
                // line 40
                if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "functions", [])) > 0)) {
                    // line 41
                    echo "            <div class=\"namespace-indent\">
                <h3><i class=\"icon-custom icon-function\"></i> Functions</h3>
                ";
                    // line 43
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "functions", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["function"]) {
                        // line 44
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["function"], "function");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['function'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 46
                    echo "            </div>
        ";
                }
                // line 48
                echo "
        ";
                // line 49
                if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0)) {
                    // line 50
                    echo "            <div class=\"namespace-indent\">
                <h3><i class=\"icon-custom icon-constant\"></i> Constants</h3>
                ";
                    // line 52
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "constants", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
                        // line 53
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["constant"], "constant");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 55
                    echo "            </div>
        ";
                }
                // line 57
                echo "
        ";
                // line 58
                if ((((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "classes", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "traits", [])) > 0))) {
                    // line 59
                    echo "            <div class=\"namespace-indent\">
                <h3><i class=\"icon-custom icon-class\"></i> Classes, interfaces and traits</h3>
                ";
                    // line 61
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "traits", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["trait"]) {
                        // line 62
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["trait"], "trait");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trait'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 64
                    echo "
                ";
                    // line 65
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "interfaces", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                        // line 66
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["interface"], "interface");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 68
                    echo "
                ";
                    // line 69
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "classes", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                        // line 70
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["class"], "class");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 72
                    echo "            </div>
        ";
                }
                // line 74
                echo "    ";
            }
            // line 75
            echo "
    ";
            // line 76
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "children", []));
            foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
                // line 77
                echo "        ";
                echo $context["self"]->getrenderNamespaceDetails($context["namespace"]);
                echo "
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 81
    public function getrenderNamespaceSidebar($__node__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "node" => $__node__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 82
            echo "    ";
            $context["self"] = $this;
            // line 83
            echo "
    ";
            // line 84
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "children", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
                // line 85
                echo "    <li>
        <a href=\"";
                // line 86
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [$context["namespace"]]), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["namespace"], "name", []), "html", null, true);
                echo "\">
            <i class=\"icon-th\"></i> ";
                // line 87
                echo twig_escape_filter($this->env, $this->getAttribute($context["namespace"], "name", []), "html", null, true);
                echo "
        </a>
        <ul class=\"nav nav-list nav-namespaces\">
            ";
                // line 90
                echo $context["self"]->getrenderNamespaceSidebar($context["namespace"]);
                echo "
        </ul>
    </li>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "/namespace.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  442 => 90,  436 => 87,  430 => 86,  427 => 85,  423 => 84,  420 => 83,  417 => 82,  405 => 81,  383 => 77,  379 => 76,  376 => 75,  373 => 74,  369 => 72,  360 => 70,  356 => 69,  353 => 68,  344 => 66,  340 => 65,  337 => 64,  328 => 62,  324 => 61,  320 => 59,  318 => 58,  315 => 57,  311 => 55,  302 => 53,  298 => 52,  294 => 50,  292 => 49,  289 => 48,  285 => 46,  276 => 44,  272 => 43,  268 => 41,  266 => 40,  260 => 37,  256 => 36,  252 => 34,  250 => 33,  247 => 32,  244 => 31,  232 => 30,  212 => 26,  208 => 24,  202 => 22,  200 => 21,  197 => 20,  194 => 19,  182 => 18,  166 => 15,  160 => 14,  157 => 13,  151 => 11,  148 => 10,  145 => 9,  143 => 8,  138 => 6,  129 => 5,  116 => 4,  103 => 3,  95 => 126,  86 => 120,  80 => 117,  74 => 116,  58 => 102,  55 => 101,  52 => 100,  43 => 97,  40 => 96,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/namespace.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig//namespace.html.twig");
    }
}
