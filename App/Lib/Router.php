<?

namespace App\Lib;

class Router
{
    private  $url;
    private  $default_action = 'index';
    private  $options = [];
    private  $patterns =
        [
            ':int:' => '([0-9]+)',
            ':str:' => '([a-zA-Z\.\-_%]+)',
            ':any:' => '([a-zA-Z0-9\.\-_%]+)',
            ':par:' => '(/.*)*',
            ':name:' => '([a-zA-Z0-9\-_]+)'
        ];
    private $parsed_routes = [];
    private $routes = [];
    private $ajax;

    function __construct()
    {

        $this->url = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        if($this->url == '')
        {
            $this->url = '/';
        }
        // замена повторных слешей одинарным
        $this->url = preg_replace('/[\/]{2,}/','/',$this->url);
        $this->ajax = $this->isAjax();
    }

    /**
     * Выполняет поиск запрошенного маршрута
     * @return array массив параметров
     */
    public function find_route()
    {
        $this->prepare_routes(); // подготовка маршрутов
        $params = [];

        foreach($this->parsed_routes as $route)
        {
            if(preg_match('/'.$route['reg_exp_url'].'/',$this->url,$matches))
            {
                array_shift($matches);
                foreach($matches as $key=>$val)
                {
                    $params[$route['params'][$key]] = $val;
                }
                if(count($route['options'])!= 0)// если есть заданные параметры
                {
                    $this->options = array_merge( $route['options'] , $params );
                }else{
                    $this->options = $params;
                }
                break;
            }
        }
        $this->options['get'] = $_GET;
        $this->check_default();
        return $this->options;
    }
    private function check_default()
    {

        if(!array_key_exists('controller',$this->options))
        {
            throw new \Exception('Контроллер не найден');
        }
        if(!array_key_exists('action',$this->options))
        {
            $this->options['action'] = $this->default_action;
        }
    }


    private function prepare_routes()
    {
        foreach($this->routes as $route)
        {
            $tmp_array = $route; // временный массив чтобы сохранить уже имеющиеся параметры
            $tmp_array['modified_url'] = $route['url'];
            if(preg_match_all('/\{.+?\}/',$route['url'],$matches))
            {
                foreach($matches[0] as $key=>$match)
                {
                    $names_and_exp = explode(':',trim($match,'{}'));//обрезаем скобки и делим на
                                                                    //имя пемеремнной и регулярку
                    if(!$names_and_exp[1])
                    {
                        $names_and_exp[1] = 'any';// регулярка по умолчанию
                    }
                    $tmp_array['params'][] = $names_and_exp[0];

                    //заменяем места пемеменных плейсхолерами
                    $tmp_array['modified_url'] = preg_replace('/\{.+?\}/',':'.$names_and_exp[1].':',$tmp_array['modified_url'],1);
                }
            }
            // составляем регулярные выражения для каждого урла
            $tmp_array['reg_exp_url'] = '^'.str_replace('/','\/',$tmp_array['modified_url']).'$';

            foreach($this->patterns as  $pattern=>$reg_exp)
            {
               $tmp_array['reg_exp_url'] = preg_replace('/'.$pattern.'/',$reg_exp,$tmp_array['reg_exp_url']);
            }
            $this->parsed_routes[] = $tmp_array;
        }
    }

    //добавление роутов
    public function add($url,array $options = NULL)
    {
        $new_route['url'] = $url;
        if($options)
        {
            $new_route['options'] = $options;
        }
        $this->routes[] = $new_route;
    }

    public function addFromArray(array $routes)
    {
        foreach ($routes as $route)
        {
            $this->add($route[0],$route[1]);
        }
    }

    public static function isAjax()
    {
        return (( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));
    }
}


