<?php
namespace Library\Dc;

class GrepMachine
{
    protected $error = null;
    protected $file = null;
    protected $filter = null;
    protected $path = "/";
    protected $result = null;
    protected $term = "";

    public function __construct($term = "", $path = "/", $filter = null, $file = null)
    {
        $this->in($path);
        $this->for($term);
        $this->filter($filter);
        $this->file($file);
    }

    public function for($term)
    {
        $this->term = $term;

        return $this;
    }

    public function in($path) {
        if(!is_dir($path)) {
            $this->error = 'Path not found!';
            return $this;
        }

        $this->path = $path;

        return $this;
    }

    public function filter($filter)
    {
        $this->filter = $filter ? '/*.' . $filter : '';

        return $this;
    }

    public function file($file)
    {
        $this->file = $file;

        return $this;
    }

    public function get()
    {
        if($this->error) {
            return null;
        }

        $cron_log = !$this->file ?
            shell_exec("grep '{$this->term}' {$this->path}{$this->filter} -ir") :
            shell_exec("grep '{$this->term}' {$this->path}{$this->file} -i");

        $this->result = explode("\n", $cron_log);

        array_pop($this->result);   // remove last item (empty string due to explode on \n)

        return $this->result;
    }

    public function getError()
    {
        return $this->error;
    }
}
