require "gtk3"

require_relative 'puzle1.rb'

class Finestra < Gtk::Window
	@@red    = Gdk::RGBA::new( 255,   0, 0, 1.0)
	@@blue    = Gdk::RGBA::new(  0,   0, 1.0, 1.0)
	@@white   = Gdk::RGBA::new(1.0, 1.0, 1.0, 1.0)
	@@font = Pango::FontDescription.new('20')

	def initialize
		super
		
		#importem l'arxiu css
		css_provider = Gtk::CssProvider.new
		style_provider = Gtk::StyleProvider::PRIORITY_USER
		css_provider.load(:data=> File.read("visual.css"))
		
		#Modifiquem la finestra
		style_context.add_provider(css_provider, style_provider)
		set_title 'Puzzle 2'
		set_border_width 10
		set_default_size 400,200 
		set_window_position Gtk::WindowPosition::CENTER

		@@rf = Rfid.new
		
		# Inicialització del text inicial
		@lab = Gtk::Label.new("Please, login with your university card", {:use_underline => true})
		@lab.style_context.add_provider(css_provider, style_provider)
		@lab.override_font(@@font)
		@lab.override_color(:normal,@@white)
		@@box = Gtk::EventBox.new
		@@box.style_context.add_provider(css_provider, style_provider)
		@@box.override_background_color(:normal  , @@blue)
		@@box.add(@lab)
		@@box.set_size_request(400,150)
		
		
		#Botó ( inicialment esta desactivat)		
		@@button = Gtk::Button.new(label: 'Clear')
		@@button.style_context.add_provider(css_provider, style_provider)
		@@button.set_size_request(400,50)	
		@@button.signal_connect('clicked') {canvi}
		@@button.set_sensitive(false)
		@@button.signal_connect("destroy"){ Gtk.main_quit}
		

		#Codi inserir elements a la finestra	
		@@caixa = Gtk::Box.new(:vertical,10)
		@@caixa.style_context.add_provider(css_provider, style_provider)
		@@caixa.pack_start(@@box,:expand => false,:fill =>false,:padding => 0)
		@@caixa.pack_start(@@button,:expand => false,:fill => false,:padding => 0)
		add(@@caixa)
		
		
		def llegir
				puts "entrem a llegir"
				Thread.new{
				@@id = @@rf.read_uid.upcase
				puts @@id
				GLib::Idle.add(canvi_vermell)
			     }
			     end

		def canvi_vermell
				puts "canvi vermell"
				@@box.override_background_color(:normal  , @@red)
				@lab.set_text("uid: " + @@id)
				@@button.set_sensitive(true)
				end
			
		def canvi 
			puts "canvi a blau"
			@@box.override_background_color(:normal  , @@blue)
			@lab.set_text("Please, login with your university card")
			@@button.set_sensitive(false)
			llegir
			end
				
		show
		llegir	
	end
	
window = Finestra.new
window.show_all
Gtk.main	
end
