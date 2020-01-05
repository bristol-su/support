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

/* /file.html.twig */
class __TwigTemplate_5c03916d83301f0ecbceea5f48b62f4668d46ec347946c1dea42bf300c1d7ce3 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
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
        $this->parent = $this->loadTemplate("layout.html.twig", "/file.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_javascripts($context, array $blocks = [])
    {
        // line 4
        echo "    <script type=\"text/javascript\">
        function loadExternalCodeSnippets() {
            Array.prototype.slice.call(document.querySelectorAll('pre[data-src]')).forEach(function (pre) {
                var src = pre.getAttribute('data-src').replace( /\\\\/g, '/');
                var extension = (src.match(/\\.(\\w+)\$/) || [, ''])[1];
                var language = 'php';

                var code = document.createElement('code');
                code.className = 'language-' + language;

                pre.textContent = '';

                code.textContent = 'Loading…';

                pre.appendChild(code);

                var xhr = new XMLHttpRequest();

                xhr.open('GET', src, true);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {

                        if (xhr.status < 400 && xhr.responseText) {
                            code.textContent = xhr.responseText;

                            Prism.highlightElement(code);
                        }
                        else if (xhr.status >= 400) {
                            code.textContent = '✖ Error ' + xhr.status + ' while fetching file: ' + xhr.statusText;
                        }
                        else {
                            code.textContent = '✖ Error: File does not exist, is empty or trying to view from localhost';
                        }
                    }
                };

                xhr.send(null);
            });
        }

        \$(document).ready(function(){
            loadExternalCodeSnippets();
        });
        \$('#source-view').on('shown', function () {
            loadExternalCodeSnippets();
        })
    </script>
";
    }

    // line 54
    public function block_content($context, array $blocks = [])
    {
        // line 55
        echo "    <section class=\"row-fluid\">
        <div class=\"span2 sidebar\">
            ";
        // line 57
        $context["namespace"] = $this->getAttribute(($context["project"] ?? null), "namespace", []);
        // line 58
        echo "            ";
        $this->displayBlock("sidebarNamespaces", $context, $blocks);
        echo "
        </div>
    </section>
    <section class=\"row-fluid\">
        <div class=\"span10 offset2\">
            <div class=\"row-fluid\">
                <div class=\"span8 content file\">
                    <nav>
                        ";
        // line 67
        echo "                        ";
        // line 68
        echo "                    </nav>

                    ";
        // line 70
        if ($this->getAttribute($this->getAttribute(($context["project"] ?? null), "settings", []), "shouldIncludeSource", [])) {
            // line 71
            echo "                        <a href=\"#source-view\" role=\"button\" class=\"pull-right btn\" data-toggle=\"modal\"><i class=\"icon-code\"></i></a>
                    ";
        }
        // line 73
        echo "                    <h1><small>";
        echo twig_escape_filter($this->env, twig_join_filter(twig_slice($this->env, twig_split_filter($this->env, $this->getAttribute(($context["node"] ?? null), "path", []), "/"), 0,  -1), "/"), "html", null, true);
        echo "</small>";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "</h1>
                    <p><em>";
        // line 74
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "summary", []), "html", null, true);
        echo "</em></p>
                    ";
        // line 75
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "description", [])]);
        echo "

                    ";
        // line 77
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "traits", [])) > 0)) {
            // line 78
            echo "                    <h2>Traits</h2>
                    <table class=\"table table-hover\">
                        ";
            // line 80
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "traits", []));
            foreach ($context['_seq'] as $context["_key"] => $context["trait"]) {
                // line 81
                echo "                            <tr>
                                <td>";
                // line 82
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["trait"], "class:short"]);
                echo "</td>
                                <td><em>";
                // line 83
                echo twig_escape_filter($this->env, $this->getAttribute($context["trait"], "summary", []), "html", null, true);
                echo "</em></td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trait'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 86
            echo "                    </table>
                    ";
        }
        // line 88
        echo "
                    ";
        // line 89
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", [])) > 0)) {
            // line 90
            echo "                    <h2>Interfaces</h2>
                    <table class=\"table table-hover\">
                        ";
            // line 92
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "interfaces", []));
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 93
                echo "                            <tr>
                                <td>";
                // line 94
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["interface"], "class:short"]);
                echo "</td>
                                <td><em>";
                // line 95
                echo twig_escape_filter($this->env, $this->getAttribute($context["interface"], "summary", []), "html", null, true);
                echo "</em></td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 98
            echo "                    </table>
                    ";
        }
        // line 100
        echo "
                    ";
        // line 101
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "classes", [])) > 0)) {
            // line 102
            echo "                    <h2>Classes</h2>
                    <table class=\"table table-hover\">
                    ";
            // line 104
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "classes", []));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 105
                echo "                        <tr>
                            <td>";
                // line 106
                echo call_user_func_array($this->env->getFilter('route')->getCallable(), [$context["class"], "class:short"]);
                echo "</td>
                            <td><em>";
                // line 107
                echo twig_escape_filter($this->env, $this->getAttribute($context["class"], "summary", []), "html", null, true);
                echo "</em></td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 110
            echo "                    </table>
                    ";
        }
        // line 112
        echo "                </div>

                <aside class=\"span4 detailsbar\">
                    <dl>
                        ";
        // line 116
        if (( !twig_test_empty($this->getAttribute(($context["node"] ?? null), "package", [])) && ($this->getAttribute(($context["node"] ?? null), "package", []) != "\\"))) {
            // line 117
            echo "                            <dt>Package</dt>
                            <dd><div class=\"namespace-wrapper\">";
            // line 118
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["node"] ?? null), "subpackage", [])) ? ((($this->getAttribute(($context["node"] ?? null), "package", []) . "\\") . $this->getAttribute(($context["node"] ?? null), "subpackage", []))) : ($this->getAttribute(($context["node"] ?? null), "package", []))), "html", null, true);
            echo "</div></dd>
                        ";
        }
        // line 120
        echo "
                        ";
        // line 121
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "link", 1 => "see"])) {
                // line 122
                echo "                            ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 123
                    echo "                                <dt>See also</dt>
                            ";
                }
                // line 125
                echo "                            ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 126
                    echo "                                <dd><a href=\"";
                    echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) ? (call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) : ($this->getAttribute($context["tag"], "link", []))), "html", null, true);
                    echo "\"><div class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</div></a></dd>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 128
                echo "                        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 129
        echo "
                    </dl>
                    <h2>Tags</h2>
                    <table class=\"table table-condensed\">
                        ";
        // line 133
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (!twig_in_filter($context["tagName"], [0 => "link", 1 => "see", 2 => "package", 3 => "subpackage"])) {
                // line 134
                echo "                            <tr>
                                <th>
                                    ";
                // line 136
                echo twig_escape_filter($this->env, $context["tagName"], "html", null, true);
                echo "
                                </th>
                                <td>
                                    ";
                // line 139
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 140
                    echo "                                        ";
                    if ($this->getAttribute($context["tag"], "version", [])) {
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                    }
                    // line 141
                    echo "                                        ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 143
                echo "                                </td>
                            </tr>
                        ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 146
            echo "                            <tr><td colspan=\"2\"><em>None found</em></td></tr>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 148
        echo "                    </table>

                </aside>
            </div>

            ";
        // line 153
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0)) {
            // line 154
            echo "            <div class=\"row-fluid\">
                <section class=\"span8 content file\">
                    <h2>Constants</h2>
                </section>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 161
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "constants", []));
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
            foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
                // line 162
                echo "                    ";
                $this->displayBlock("constant", $context, $blocks);
                echo "
                ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 164
            echo "            ";
        }
        // line 165
        echo "
            ";
        // line 166
        if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "functions", [])) > 0)) {
            // line 167
            echo "            <div class=\"row-fluid\">
                <section class=\"span8 content file\">
                    <h2>Functions</h2>
                </section>
                <aside class=\"span4 detailsbar\"></aside>
            </div>

                ";
            // line 174
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "functions", []));
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
            foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
                // line 175
                echo "                    ";
                $this->displayBlock("method", $context, $blocks);
                echo "
                ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 177
            echo "            ";
        }
        // line 178
        echo "
        </div>
    </section>

    <div id=\"source-view\" class=\"modal hide fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"source-view-label\" aria-hidden=\"true\">
        <div class=\"modal-header\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
            <h3 id=\"source-view-label\">";
        // line 185
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "file", []), "name", []), "html", null, true);
        echo "</h3>
        </div>
        <div class=\"modal-body\">
            <pre data-src=\"";
        // line 188
        echo call_user_func_array($this->env->getFunction('path')->getCallable(), [(("files/" . $this->getAttribute(($context["node"] ?? null), "path", [])) . ".txt")]);
        echo "\" class=\"language-php line-numbers\"></pre>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "/file.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  479 => 188,  473 => 185,  464 => 178,  461 => 177,  444 => 175,  427 => 174,  418 => 167,  416 => 166,  413 => 165,  410 => 164,  393 => 162,  376 => 161,  367 => 154,  365 => 153,  358 => 148,  351 => 146,  343 => 143,  334 => 141,  329 => 140,  325 => 139,  319 => 136,  315 => 134,  309 => 133,  303 => 129,  293 => 128,  282 => 126,  277 => 125,  273 => 123,  270 => 122,  259 => 121,  256 => 120,  251 => 118,  248 => 117,  246 => 116,  240 => 112,  236 => 110,  227 => 107,  223 => 106,  220 => 105,  216 => 104,  212 => 102,  210 => 101,  207 => 100,  203 => 98,  194 => 95,  190 => 94,  187 => 93,  183 => 92,  179 => 90,  177 => 89,  174 => 88,  170 => 86,  161 => 83,  157 => 82,  154 => 81,  150 => 80,  146 => 78,  144 => 77,  139 => 75,  135 => 74,  128 => 73,  124 => 71,  122 => 70,  118 => 68,  116 => 67,  104 => 58,  102 => 57,  98 => 55,  95 => 54,  43 => 4,  40 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/file.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean//file.html.twig");
    }
}
