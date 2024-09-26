<?php

declare(strict_types = 1);

namespace App\Http;

use App\Http\Response;
use App\App;

class View extends Response
{

    public function __construct(protected string $view, protected array $params = [], int $statusCode = 200)
    {
        parent::__construct(statusCode: $statusCode, headers: ['Content-Type' => 'text/html'], body: '');
    }

    public static function make(string $view, array $params = [], int $statusCode = 200): static
    {
        return new static(view: $view, params: $params, statusCode: $statusCode);
    }

    public function render(): string
    {   
        $data = $this->params['data'] ?? null;
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        $view = $this->obLoadView($viewPath, $data);

        if(!$view){
            self::setStatusCode(404);
            return '404 - no view';
        }

        $view = $this->loadLayout($view);

        if(!$view){
            self::setStatusCode(404);
            return '404 - no view';
        }

        return $view;
    }

    private function obLoadView($viewPath, $data = null): ?string
    {
        if(!file_exists($viewPath)){
            return null;
        }

        ob_start();
        include $viewPath;
        return (string) ob_get_clean();
    }

    private function loadLayout(string $view): string
    {
        $patternLayoutDeclare = '/{% load_layout "([\w\/_-]+)" %}/';

        $layoutView = null;

        if(preg_match($patternLayoutDeclare, $view, $layoutMatches)){
            array_shift($layoutMatches);

            if(count($layoutMatches) > 1) 
                throw new \Exception('Declare more then one layout');

            $path = $layoutMatches[0] ?? '';
            $layoutView = $this->obLoadView(VIEW_PATH . '/' . $path . '.php');
            if(!$layoutView) return preg_replace($patternLayoutDeclare, '', $view);
            
            $layoutView = $this->handleLayoutFill($view, $layoutView);
        }

        if(!$layoutView) return preg_replace($patternLayoutDeclare, '', $view);

        return $layoutView;
    }

    private function handleLayoutFill($view, $layout): string
    {
        $patternOpen = '/{% open ([\w]+) %}/';
        $contentToReplace = $this->getContentToReplace($view);

        if(preg_match_all($patternOpen, $layout, $htmlToReplaceMatches)){
            $tags = $htmlToReplaceMatches[1] ?? [];

            foreach($tags as $tag) {
                $openDeclare = '{% open ' . $tag . ' %}';
                $closeDeclare = '{% close ' . $tag . ' %}';

                if(empty($contentToReplace[$tag])){
                    $layout = str_replace([$openDeclare, $closeDeclare], '', $layout);
                    continue;
                }
                if(strpos($layout, $closeDeclare) === false)
                    throw new \Exception("There is no close declare for tag; '$tag'");
                
                $start = strpos($layout, $openDeclare);
                $end = strpos($layout, $closeDeclare) + strlen($closeDeclare);
                
                $layout = substr_replace($layout, $contentToReplace[$tag], $start, $end - $start);
            }           
        }

        return $layout;
    }

    private function getContentToReplace($view): array
    {
        $patternOpen = '/{% open ([\w]+) %}/';
        $countMatches = preg_match_all($patternOpen, $view, $htmlToReplaceMatches, PREG_OFFSET_CAPTURE);
        
        $contentToReplace = [];

        if($countMatches == 0) return [];

        $declareMatches = $htmlToReplaceMatches[0];
        $tagsMatches = $htmlToReplaceMatches[1];

        for($i = 0; $i < count($declareMatches); $i++) { 
            // index 0 is string match
            // index 1 is strpos of match

            $tag = $tagsMatches[$i][0];
            $closeDeclare = '{% close ' . $tag . ' %}';

            if(strpos($view, $closeDeclare) === false)
                throw new \Exception("There is no close declare for tag; '$tag'");

            $start = $declareMatches[$i][1] + strlen($declareMatches[$i][0]);
            $end = strpos($view, $closeDeclare);

            $length = $end - $start;

            $contentToReplace[$tag] = substr($view, $start, $length);
        }

        return $contentToReplace;
    }

    public function __toString(): string
    {
        $body = $this->render();
        self::setBody($body);
        
        return parent::__toString();
    }
}