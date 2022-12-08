<?php

if (! function_exists('redirectNotAuthorized')) {
  
  /**
   * Function to compacting redirect->with
   */
  function redirectNotAuthorized($url)
  {
    return redirect($url)->with('warning', 'Not Authorized');
  }
}

if (! function_exists('redirectWithAlert')) {
  
  /**
   * Function to compacting redirect->with
   */
  function redirectWithAlert($url, $mode, $msg)
  {
    return redirect($url)->with($mode, $msg);
  }
}