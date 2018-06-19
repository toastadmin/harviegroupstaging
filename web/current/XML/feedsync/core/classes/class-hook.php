<?php

	class hook {
		
		public $filters;
		public $current_filter;
		
		function hooks_api() {
		
		}
		
		function add_filter($tag,$callback,$priority=10,$accepted_args=1) {
			$unique_id = uniqid('filter_');
			$this->filters[$tag][$priority][$unique_id] = array(
				'function'			=>	$callback,
				'accepted_args'		=>	$accepted_args
			);
		}
		
		function apply_filters($tag,$value) {
			
			$args = func_get_args();

			if( !empty($this->filters) ) {
				if( array_key_exists($tag,$this->filters) ) {
					$this->current_filter = $this->filters[$tag];
					
					krsort($this->current_filter);

					foreach( $this->current_filter as $priority ) {
					
						foreach( $priority as $callbacks ) {
							
							$func_args = array_slice($args, $callbacks['accepted_args']);
							
							if( function_exists($callbacks['function']) )
								$value =  call_user_func_array($callbacks['function'], $func_args);

						}

					}
				}
			}
				
			
			return $value;
		}
		
		function do_action($tag) {
			
			$args = func_get_args();

			if( array_key_exists($tag,$this->actions) ) {
				$this->current_action = $this->actions[$tag];
				
				krsort($this->current_action);

				foreach( $this->current_action as $priority ) {
				
					foreach( $priority as $callbacks ) {
						
						$func_args = array_slice($args, $callbacks['accepted_args']);
						
						if( function_exists($callbacks['function']) )
							call_user_func_array($callbacks['function'], $func_args);

					}

				}
			}
			
		}
		
		function add_action($tag,$callback,$priority=10,$accepted_args=1) {
			$unique_id = uniqid('action_');
			$this->actions[$tag][$priority][$unique_id] = array(
				'function'			=>	$callback,
				'accepted_args'		=>	$accepted_args
			);
		}


	}
	
	$feedsync_hook = new hook();
?>
