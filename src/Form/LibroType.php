<?php
    namespace App\Form;

    class LibroType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder->add('isbn', TextType::class)
                    ->add('titulo', TextType::class)
                    ->add('autor', TextType::class)
                    ->add('paginas', NumberType::class)
                    ->add('save', SubmitedType::class, array('label' => 'Crear'));
        }
    }

?>