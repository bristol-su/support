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

/* elements/method.html.twig */
class __TwigTemplate_b10e370121fb5d8150eead50b518ab11c7f4b407923358af2bab255e5bf6675f extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'method' => [$this, 'block_method'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $this->displayBlock('method', $context, $blocks);
    }

    public function block_method($context, array $blocks = [])
    {
        // line 2
        echo "    <div class=\"row-fluid\">
        <div class=\"span8 content class\">
            <a id=\"method_";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "name", []), "html", null, true);
        echo "\" name=\"method_";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "name", []), "html", null, true);
        echo "\" class=\"anchor\"></a>
            <article class=\"method\">
                <h3 class=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "visibility", []), "html", null, true);
        echo " ";
        if ($this->getAttribute(($context["method"] ?? null), "deprecated", [])) {
            echo "deprecated";
        }
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "name", []), "html", null, true);
        echo "()</h3>
                <a href=\"#source-view\" role=\"button\" class=\"pull-right btn\" data-toggle=\"modal\" style=\"font-size: 1.1em; padding: 9px 14px\"><i class=\"icon-code\"></i></a>
                <pre class=\"signature\" style=\"margin-right: 54px;\">";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "name", []), "html", null, true);
        echo "(";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "arguments", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["argument"]) {
            (($this->getAttribute($context["argument"], "types", [])) ? (print (twig_escape_filter($this->env, (twig_join_filter($this->getAttribute($context["argument"], "types", []), "|") . " "), "html", null, true))) : (print ("")));
            echo " <span class=\"argument\">";
            echo (($this->getAttribute($context["argument"], "isVariadic", [])) ? ("...") : (""));
            echo twig_escape_filter($this->env, $this->getAttribute($context["argument"], "name", []), "html", null, true);
            (($this->getAttribute($context["argument"], "default", [])) ? (print (twig_escape_filter($this->env, (" = " . $this->getAttribute($context["argument"], "default", [])), "html", null, true))) : (print ("")));
            echo "</span>";
            if ( !$this->getAttribute($context["loop"], "last", [])) {
                echo ", ";
            }
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['argument'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        echo ") ";
        (($this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", [])) ? (print (twig_escape_filter($this->env, (": " . twig_join_filter($this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", []), "|")), "html", null, true))) : (print ("")));
        echo "</pre>
                <p><em>";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "summary", []), "html", null, true);
        echo "</em></p>
                ";
        // line 10
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["method"] ?? null), "description", [])]);
        echo "

                ";
        // line 12
        if ((twig_length_filter($this->env, $this->getAttribute(($context["method"] ?? null), "arguments", [])) > 0)) {
            // line 13
            echo "                    <h4>Parameters</h4>
                    <table class=\"table table-condensed table-hover\">
                        ";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "arguments", []));
            foreach ($context['_seq'] as $context["_key"] => $context["argument"]) {
                // line 16
                echo "                            <tr>
                                <td>";
                // line 17
                echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["argument"], "types", [])]), "|");
                echo "</td>
                                <td>";
                // line 18
                echo twig_escape_filter($this->env, $this->getAttribute($context["argument"], "name", []), "html", null, true);
                echo " ";
                echo (($this->getAttribute($context["argument"], "isVariadic", [])) ? ("<small style=\"color: gray\">variadic</small>") : (""));
                echo "</td>
                                <td>";
                // line 19
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["argument"], "description", [])]);
                echo "</td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['argument'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 22
            echo "                    </table>
                ";
        }
        // line 24
        echo "
                ";
        // line 25
        if (((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throws", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throw", [])) > 0))) {
            // line 26
            echo "                    <h4>Throws</h4>
                    <dl>
                        ";
            // line 28
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throws", []));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 29
                echo "                            <dt>";
                echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["exception"], "types", [])]), "|");
                echo "</dt>
                            <dd>";
                // line 30
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["exception"], "description", [])]);
                echo "</dd>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 32
            echo "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "throw", []));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 33
                echo "                            <dt>";
                echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["exception"], "types", [])]), "|");
                echo "</dt>
                            <dd>";
                // line 34
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["exception"], "description", [])]);
                echo "</dd>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 36
            echo "                    </dl>
                ";
        }
        // line 38
        echo "
                ";
        // line 39
        if (($this->getAttribute(($context["method"] ?? null), "response", []) && (twig_join_filter($this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", [])) != "void"))) {
            // line 40
            echo "                    <h4>Returns</h4>
                    ";
            // line 41
            echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "types", [])]), "|");
            echo "
                    ";
            // line 42
            if ($this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "description", [])) {
                // line 43
                echo "                        &mdash; ";
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($this->getAttribute(($context["method"] ?? null), "response", []), "description", [])]);
                echo "
                    ";
            }
            // line 45
            echo "                ";
        }
        // line 46
        echo "
                ";
        // line 47
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "example"])) {
                // line 48
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 49
                    echo "                        <h4>Examples</h4>
                    ";
                }
                // line 51
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 52
                    echo "                        <h5>";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "description", []));
                    echo "</h5>
                        <pre class=\"pre-scrollable\">";
                    // line 53
                    echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "example", []));
                    echo "</pre>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 55
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 56
        echo "            </article>
        </div>
        <aside class=\"span4 detailsbar\">
            <h1><i class=\"icon-arrow-down\"></i></h1>
            ";
        // line 60
        if ($this->getAttribute(($context["method"] ?? null), "static", [])) {
            // line 61
            echo "                <span class=\"label label-info\">static</span>
            ";
        }
        // line 63
        echo "            ";
        if ($this->getAttribute(($context["method"] ?? null), "abstract", [])) {
            // line 64
            echo "                <span class=\"label label-info\">abstract</span>
            ";
        }
        // line 66
        echo "            ";
        if ($this->getAttribute(($context["method"] ?? null), "final", [])) {
            // line 67
            echo "                <span class=\"label label-info\">final</span>
            ";
        }
        // line 69
        echo "            ";
        if ($this->getAttribute(($context["method"] ?? null), "deprecated", [])) {
            // line 70
            echo "                <aside class=\"alert alert-block alert-error\">
                    <h4>Deprecated";
            // line 71
            if ($this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "version", [])) {
                echo " since ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "version", []), "html", null, true);
            }
            echo "</h4>
                    ";
            // line 72
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["method"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "description", []), "html", null, true);
            echo "
                </aside>
            ";
        }
        // line 75
        echo "            <dl>
                ";
        // line 76
        if ((null === $this->getAttribute(($context["method"] ?? null), "parent", []))) {
            // line 77
            echo "                    <dt>File</dt>
                    <dd><a href=\"";
            // line 78
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["method"] ?? null), "file", []), "url"]), "html", null, true);
            echo "\"><div class=\"path-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? null), "path", []), "html", null, true);
            echo "</div></a></dd>
                ";
        }
        // line 80
        echo "                ";
        if (( !(null === $this->getAttribute(($context["method"] ?? null), "parent", [])) && ($this->getAttribute($this->getAttribute(($context["method"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []) != $this->getAttribute(($context["method"] ?? null), "fullyQualifiedStructuralElementName", [])))) {
            // line 81
            echo "                    <dt>Inherited from</dt>
                    <dd><a href=\"";
            // line 82
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["method"] ?? null), "parent", []), "url"]), "html", null, true);
            echo "\"><div class=\"path-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["method"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []), "html", null, true);
            echo "</div></a></dd>
                ";
        }
        // line 84
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "link", 1 => "see"])) {
                // line 85
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 86
                    echo "                        <dt>See also</dt>
                    ";
                }
                // line 88
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 89
                    echo "                        <dd><a href=\"";
                    echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) ? (call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) : ($this->getAttribute($context["tag"], "link", []))), "html", null, true);
                    echo "\"><span class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</span></a></dd>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 91
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 92
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "uses"])) {
                // line 93
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 94
                    echo "                        <dt>Uses</dt>
                    ";
                }
                // line 96
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 97
                    echo "                        <dd><a href=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"]), "html", null, true);
                    echo "\"><span class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</span></a></dd>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 99
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 100
        echo "            </dl>
            <h2>Tags</h2>
            <table class=\"table table-condensed\">
                ";
        // line 103
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? null), "tags", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (!twig_in_filter($context["tagName"], [0 => "link", 1 => "see", 2 => "abstract", 3 => "example", 4 => "param", 5 => "return", 6 => "access", 7 => "deprecated", 8 => "throws", 9 => "throw", 10 => "uses"])) {
                // line 104
                echo "                    <tr>
                        <th>
                            ";
                // line 106
                echo twig_escape_filter($this->env, $context["tagName"], "html", null, true);
                echo "
                        </th>
                        <td>
                            ";
                // line 109
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 110
                    echo "                                ";
                    if ($this->getAttribute($context["tag"], "version", [])) {
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                    }
                    // line 111
                    echo "                                ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 113
                echo "                        </td>
                    </tr>
                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 116
            echo "                    <tr><td colspan=\"2\"><em>None found</em></td></tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 118
        echo "            </table>
        </aside>
    </div>
";
    }

    public function getTemplateName()
    {
        return "elements/method.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  485 => 118,  478 => 116,  470 => 113,  461 => 111,  456 => 110,  452 => 109,  446 => 106,  442 => 104,  436 => 103,  431 => 100,  421 => 99,  410 => 97,  405 => 96,  401 => 94,  398 => 93,  386 => 92,  376 => 91,  365 => 89,  360 => 88,  356 => 86,  353 => 85,  341 => 84,  334 => 82,  331 => 81,  328 => 80,  321 => 78,  318 => 77,  316 => 76,  313 => 75,  307 => 72,  300 => 71,  297 => 70,  294 => 69,  290 => 67,  287 => 66,  283 => 64,  280 => 63,  276 => 61,  274 => 60,  268 => 56,  258 => 55,  250 => 53,  245 => 52,  240 => 51,  236 => 49,  233 => 48,  222 => 47,  219 => 46,  216 => 45,  210 => 43,  208 => 42,  204 => 41,  201 => 40,  199 => 39,  196 => 38,  192 => 36,  184 => 34,  179 => 33,  174 => 32,  166 => 30,  161 => 29,  157 => 28,  153 => 26,  151 => 25,  148 => 24,  144 => 22,  135 => 19,  129 => 18,  125 => 17,  122 => 16,  118 => 15,  114 => 13,  112 => 12,  107 => 10,  103 => 9,  59 => 8,  48 => 6,  41 => 4,  37 => 2,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "elements/method.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean/elements/method.html.twig");
    }
}
