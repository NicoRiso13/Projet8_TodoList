<?php
//
//namespace Validator;
//
//use App\Repository\TricksRepository;
//use Doctrine\ORM\NonUniqueResultException;
//use Doctrine\ORM\NoResultException;
//use Symfony\Component\Form\Exception\UnexpectedTypeException;
//use Symfony\Component\Validator\Constraint;
//use Symfony\Component\Validator\ConstraintValidator;
//use Symfony\Component\Validator\Exception\UnexpectedValueException;
//
//class UniqueUserNameValidator extends ConstraintValidator
//{
//    private TricksRepository $tricksRepository;
//
//    /**
//     * @throws NonUniqueResultException
//     * @throws NoResultException
//     */
//    public function validate($value, Constraint $constraint): void
//    {
//        if (!$constraint instanceof UniqueUserName) {
//            throw new UnexpectedTypeException($constraint, UniqueUserName::class);
//        }
//
//        if (null === $value || '' === $value) {
//            return;
//        }
//
//        if (!is_string($value)) {
//
//            throw new UnexpectedValueException($value, 'string');
//
//        }
//
//        if ($this->tricksRepository->countName($value) > 0) {
//
//            $this->context->buildViolation($constraint->message)
//                ->setParameter('{{string}}', $value)
//                ->addViolation();
//        }
//    }
//    public function __construct(TricksRepository $trickRepository)
//    {
//        $this->tricksRepository = $trickRepository;
//    }
//
//
//}
//
