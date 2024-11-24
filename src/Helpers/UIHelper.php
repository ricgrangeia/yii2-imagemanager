<?php
/**
 * @author Ricardo Grangeia Dias <ricardograngeia@gmail.com>
 * @profile PHP Developer
 *
 * @company Sabores do Futuro
 * Copyright All rights reserved.
 */

namespace ricgrangeia\yii2ImageManager\Helpers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Alert;


final class UIHelper {

	public static function UICrudFooterUpdate( int $id ): string {

		return Html::button( Yii::t( 'yii2-ajaxcrud', 'Close' ), [ 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal" ] ) .
			Html::a( Yii::t( 'yii2-ajaxcrud', 'Update' ), [ 'update', 'id' => $id ], [ 'class' => 'btn btn-primary', 'role' => 'modal-remote' ] );
	}

	public static function UICrudFooterCreate(): string {

		return Html::button( Yii::t( 'yii2-ajaxcrud', 'Close' ), [ 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal" ] ) .
			Html::button( Yii::t( 'yii2-ajaxcrud', 'Create' ), [ 'class' => 'btn btn-primary', 'type' => "submit" ] );
	}

	public static function UICrudFooterCreateMore(): string {

		return Html::button( Yii::t( 'yii2-ajaxcrud', 'Close' ), [ 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal" ] ) .
			Html::a( Yii::t( 'yii2-ajaxcrud', 'Create More' ), [ 'create' ], [ 'class' => 'btn btn-primary', 'role' => 'modal-remote' ] );
	}

	public static function UICrudFooterSave(): string {

		return Html::button( Yii::t( 'yii2-ajaxcrud', 'Close' ), [ 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal" ] ) .
			Html::button( Yii::t( 'yii2-ajaxcrud', 'Save' ), [ 'data-pjax' => false, 'class' => 'btn btn-primary', 'type' => "submit", 'data-dismiss' => "modal" ] );
	}

	public static function UICrudGridActions(): array {

		return [
			'class' => \kartik\grid\ActionColumn::class,
			'dropdown' => false,
			'noWrap' => 'true',
			'template' => '{view} {update} {delete}',
			'vAlign' => 'middle',
			'urlCreator' => function ( $action, $model, $key, $index ) {

				return Url::to( [ $action, 'id' => $key ] );
			},
			'viewOptions' => [ 'role' => 'modal-remote', 'title' => Yii::t( 'yii2-ajaxcrud', 'View' ), 'data-toggle' => 'tooltip', 'class' => 'btn btn-sm btn-outline-success' ],
			'updateOptions' => [ 'role' => 'modal-remote', 'title' => Yii::t( 'yii2-ajaxcrud', 'Update' ), 'data-toggle' => 'tooltip', 'class' => 'btn btn-sm btn-outline-primary' ],
			'deleteOptions' => [ 'role' => 'modal-remote', 'title' => Yii::t( 'yii2-ajaxcrud', 'Delete' ), 'class' => 'btn btn-sm btn-outline-danger',
				'data-confirm' => false,
				'data-method' => false,// for overide yii data api
				'data-request-method' => 'post',
				'data-toggle' => 'tooltip',
				'data-confirm-title' => Yii::t( 'yii2-ajaxcrud', 'Delete' ),
				'data-confirm-message' => Yii::t( 'yii2-ajaxcrud', 'Delete Confirm' ) ],
		];
	}

	public static function UICrudGridActionsDropDown(): array {

		$actions = self::UICrudGridActions();
		$actions['dropdown'] = true;
		$actions['dropdownButton'] = [ 'class' => 'btn btn-outline-secondary btn-sm py-0' ];

		return $actions;
	}

	public static function UI_loader_spinner( string $load_text = 'A carregar...' ): string {

		return <<< HTML
			<div id="spinner">
				<button class="btn btn-primary" type="button" disabled>
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					<span class="visually-hidden">$load_text</span>
				</button>
			</div>
		HTML;
	}

	public static function UI_alert_info_without_sells(): string {

		return Alert::widget( [
			'options' => [ 'class' => 'alert alert-info ml-1 mr-1' ],
			'body' => '<b>Dia sem vendas!</b>',
			'closeButton' => false,
		] );
	}

	public static function showErrorsSummary( object $model ): bool|string {

		// Start output buffering
		ob_start();

		if ( $model->hasErrors() ): ?>
            <div class="custom-error-summary">
                <strong><?= Yii::t( 'app', 'There were some errors:' ) ?></strong>
                <ul>
					<?php foreach ( $model->getErrors() as $errors ): ?>
						<?php foreach ( $errors as $error ): ?>
                            <li><i class="fas fa-exclamation-circle"></i> <?= Html::encode( $error ) ?></li>
						<?php endforeach; ?>
					<?php endforeach; ?>
                </ul>
            </div>
		<?php endif;

		// Get the contents of the output buffer and clean it
		return ob_get_clean();
	}

}