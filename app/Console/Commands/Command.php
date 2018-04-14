<?php

namespace App\Console\Commands;

trait Command
{
    public function validate($rules, $input = null)
    {
        $v = validator($input ? $input : $this->argument(), $rules);

        if ($v->fails()) {
            $this->line('');
            foreach ($v->errors()->all() as $error) {
                $this->error($error);
            }

            die();
        }
    }

    /**
     * Write a string in an alert box.
     *
     * @param  string  $string
     * @return void
     */
    public function alert($string)
    {
        $this->error(str_repeat(' ', strlen($string) + 12));
        $this->error('      '.$string.'      ');
        $this->error(str_repeat(' ', strlen($string) + 12));

        $this->output->newLine();
    }

    /**
     * Write a string in an alert box.
     *
     * @param  string  $string
     * @return void
     */
    public function warning($string)
    {
        $this->output->newLine();
        $this->output->newLine();

        $this->error(str_repeat(' ', strlen($string) + 12));
        $this->error('      '.$string.'      ');
        $this->error(str_repeat(' ', strlen($string) + 12));

        $this->output->newLine();
        $this->output->newLine();
    }
}
