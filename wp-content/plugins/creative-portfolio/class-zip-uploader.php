<?php

class ZIP_Uploader {

	protected $folder = '';
	private $archive  = 'Rich_Media_Archive';

	public function __construct( $folder ) {
		$this->folder = $folder;
	}


	/**
	 * Get folder name where to upload
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_folder_name( $filename ) {
		return sanitize_title( $filename );
	}

	/**
	 * Get target path for the parent folder where all files are uploaded
	 *
	 * @return string
	 */
	public function get_target_path() {
		$upload_directory = wp_get_upload_dir();
		$upload_baseurl   = $upload_directory['basedir'];
		return trailingslashit( $upload_baseurl ) . $this->folder;
	}

	/**
	 * Get path
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_folder_path( $folder ) {
		return trailingslashit( $this->get_target_path() ) . $folder;
	}

    /**
	 * Upload File
	 *
	 * @param $data
	 *
	 * @return bool|string|true|WP_Error
	 */
	public function upload( $data ) {
		
		/** @var $wp_filesystem \WP_Filesystem_Direct */
		global $wp_filesystem;
    
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once 'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();

		$wpError = new WP_Error();

		$zip_file_url 	= $data['file'];
		$file_name 		= wp_basename( $zip_file_url );
		$base_file_name = wp_basename( $zip_file_url , '.zip' );
		
		$file_name_arr = explode( '.', $file_name );
		$extension 	   = array_pop( $file_name_arr );
		if ( 'zip' !== $extension ) {
			$wpError->add( 'no-zip', 'This is not a compressed .zip file' );
			return $wpError;
		}
		
		$destination = wp_upload_dir();
		$working_dir = $destination['path'];
		$upload_dir  = $destination['basedir'];
		$file_base_url = $working_dir.'/'.$file_name; // .zip file url in uploads directory
		$target_folder = $this->get_folder_path( $base_file_name );
		$file_attachment_id = attachment_url_to_postid( $zip_file_url );
		$archive_path = $upload_dir.'/'.$this->archive;		
		$archive_folder = $archive_path.'/'.$base_file_name;

		if( file_exists( $file_base_url ) ) {
			
			// check if the file has already been extracted
			if ( is_dir( $archive_folder ) ) {

				// remove newly added file which was previously extracted 
				if ( file_exists( $file_base_url ) ) {
					//delete uploaded zip file from media library
					wp_delete_attachment( $file_attachment_id, true ); // true: force delete the actual file and all related DB record						
				}

				// $wpError->add('extracted', 'This file has been extracted already');
				// return $wpError;

			} else {

				$unzipfile = unzip_file( $file_base_url , $this->get_target_path() );

				if( $unzipfile ) {

					if ( !is_dir( $target_folder ) ) {		
						$wpError->add('extract-fail', 'File extraction failed. Please try again');
					} 
					elseif ( !file_exists( $target_folder.'/index.html' ) ) {
						$wpError->add('improper-file', 'This .zip file is not accurate for rich media content. Extraction Failed. Please try again');
					} 
					else {
						// store proper zip file contents
						$archivefile = unzip_file( $file_base_url , $archive_path );
					}					
					
					$wp_filesystem->delete( $this->get_target_path(), true, 'd' ); // remove incorrect zip folder contents with tmp folder						
					
					// delete uploaded zip file from media library
					wp_delete_attachment( $file_attachment_id, true ); // true: force delete the actual file and all related DB record	
				}
				else {
					if( is_wp_error( $unzipfile ) ) {
						$wpError->add('file-error', 'This compressed file was not compressed properly.');						
					}

					if ( is_dir( $this->get_target_path() ) ) {
						$wp_filesystem->delete( $this->get_target_path(), true, 'd' ); // remove incorrect zip folder contents with tmp folder						
					}
				}				
									
				return $wpError;
			}

		} else {
			$wpError->add('file-not-exist', 'This file does not exist in uploads directory.');			
			return $wpError;
		}			
	}


}