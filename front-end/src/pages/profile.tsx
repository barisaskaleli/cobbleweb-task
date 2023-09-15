import {useEffect, useState} from 'react';
import {useSelector, useDispatch} from 'react-redux';
import {RootState} from '../store';
import {fetchUserProfile} from '../slices/userSlice';
import Cookies from 'js-cookie';
import {
    ChakraProvider,
    Box,
    Text,
    Spinner,
    useColorModeValue,
    Flex,
    Stack,
    Button,
    Menu, MenuButton, Avatar, MenuList, Center, IconButton, useBreakpointValue,
} from '@chakra-ui/react';
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import Slider from "react-slick";
import {ArrowBackIcon, ArrowForwardIcon} from '@chakra-ui/icons';

const Profile = () => {
    const dispatch = useDispatch();
    const user = useSelector((state: RootState) => state.user);
    const token = Cookies.get('token');
    const [slider, setSlider] = useState(null);

    useEffect(() => {
        if (token) {
            // @ts-ignore
            dispatch(fetchUserProfile(token));
        }
    }, [dispatch, token]);

    if (user.status === 'loading') {
        return (
            <ChakraProvider>
                <Box padding="20px" textAlign="center">
                    <Spinner/>
                </Box>
            </ChakraProvider>
        );
    }

    if (user.status === 'failed') {
        return (
            <ChakraProvider>
                <Box padding="20px" textAlign="center">
                    <Text color="red.500">Unauthorized</Text>
                </Box>
            </ChakraProvider>
        );
    }

    const settings = {
        dots: true,
        arrows: false,
        fade: true,
        infinite: true,
        autoplay: true,
        speed: 500,
        autoplaySpeed: 5000,
        slidesToShow: 1,
        slidesToScroll: 1,
    };

    return (
        <><Box bg={useColorModeValue('gray.100', 'gray.900')} px={4}>
            <Flex h={16} alignItems={'center'} justifyContent={'flex-end'}>
                <Flex alignItems={'center'}>
                    <Text>{user.fullName}</Text>
                    <Stack direction={'row'} spacing={7}>
                        <Menu>
                            <MenuButton
                                as={Button}
                                rounded={'full'}
                                variant={'link'}
                                cursor={'pointer'}
                                minW={0}>
                                <Avatar
                                    size={'sm'}
                                    src={user.avatar}/>
                            </MenuButton>
                            <MenuList alignItems={'center'}>
                                <Center>
                                    <Avatar
                                        size={'2xl'}
                                        src={user.avatar}/>
                                </Center>
                            </MenuList>
                        </Menu>
                    </Stack>
                </Flex>
            </Flex>
        </Box><Box position={'relative'} height={'600px'} width={'full'} overflow={'hidden'}>
            <IconButton
                aria-label="left-arrow"
                colorScheme="messenger"
                borderRadius="full"
                position="absolute"
                left={{base: '50%', md: '95%'}}
                top={{base: '90%', md: '50%'}}
                transform={'translate(0%, -50%)'}
                zIndex={2}
                onClick={() => slider?.slickPrev()}>
                <ArrowForwardIcon/>
            </IconButton>
            <IconButton
                aria-label="right-arrow"
                colorScheme="messenger"
                borderRadius="full"
                position="absolute"
                left={{base: '30%', md: '30px'}}
                top={{base: '90%', md: '50%'}}
                transform={'translate(0%, -50%)'}
                zIndex={2}
                onClick={() => slider?.slickNext()}>
                <ArrowBackIcon/>
            </IconButton>
            {user.photos && user.photos.length > 0 && (
                <Slider {...settings} ref={(slider) => setSlider(slider as any)}>
                    {user.photos.map((photo, index) => (
                        <Box
                            key={index}
                            height={'6xl'}
                            position="relative"
                            backgroundPosition="center"
                            backgroundRepeat="no-repeat"
                            backgroundSize="cover"
                            backgroundImage={`url(${photo.url})`}/>
                    ))}
                </Slider>
            )}
        </Box></>
    );
};

export default Profile;