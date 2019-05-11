<?php
	namespace JWP\CCF\DH;
	defined( 'ABSPATH' ) || exit;

	/**
	 * JWP\CCF\DH\Handler - базовый класс пользовательских обработчиков
	 * 
	 * Базовый класс для пользовательских обработчиков. Необходима реализация абстрактных методов
	 */
	abstract class Handler extends Handler_Base {
		/**
		 * В этом методе необходимо реализовать механизм обработки данных
		 *
		 * @param JWP\CCF\DH\Request $request
		 * @param JWP\CCF\DH\Response $response
		 * @return JWP\CCF\DH\Response
		 */
		abstract public function process( $request, $response );
		
		/**
		 * Вычисляет и возвращает максимальное кол-во элементов в выборке. Необходима реализация.
		 *
		 * @param JWP\CCF\DH\Request $request
		 * @return int
		 */
		abstract public function total( $request );
	}

